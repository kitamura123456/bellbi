import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import Peer from 'simple-peer';

// Pusherをwindowに設定（Laravel Echoで使用）
window.Pusher = Pusher;

// Laravel Echoの初期化
let echo = null;

function initializeEcho() {
    if (echo) {
        return echo;
    }

    // Pusher設定を取得
    const pusherKey = document.querySelector('meta[name="pusher-key"]')?.getAttribute('content') || 
                      window.PUSHER_APP_KEY || 
                      'your-app-key';
    const pusherCluster = document.querySelector('meta[name="pusher-cluster"]')?.getAttribute('content') || 
                          window.PUSHER_APP_CLUSTER || 
                          'mt1';

    console.log('Initializing Echo with Pusher:', { key: pusherKey, cluster: pusherCluster });

    try {
        // ベースパスを取得（グローバルに設定されている場合）
        const basePath = window.BASE_PATH || '';
        
        echo = new Echo({
            broadcaster: 'pusher',
            key: pusherKey,
            cluster: pusherCluster,
            forceTLS: true,
            encrypted: true,
            authEndpoint: `${basePath}/broadcasting/auth`,
            auth: {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
            },
        });

        // 接続エラーのハンドリング
        echo.connector.pusher.connection.bind('error', (err) => {
            console.error('Pusher connection error:', err);
        });

        echo.connector.pusher.connection.bind('connected', () => {
            console.log('Pusher connected successfully');
        });

        echo.connector.pusher.connection.bind('disconnected', () => {
            console.log('Pusher disconnected');
        });

        return echo;
    } catch (error) {
        console.error('Failed to initialize Echo:', error);
        throw error;
    }
}

class VideoCallManager {
    constructor(conversationId, currentUserId, basePath = null) {
        this.conversationId = conversationId;
        this.currentUserId = currentUserId;
        
        // ベースパスを取得（引数で渡された場合はそれを使用、なければ自動取得）
        this.basePath = basePath || this.getBasePath();
        // グローバルに設定（Echoの初期化でも使用）
        window.BASE_PATH = this.basePath;
        console.log('VideoCallManager initialized with basePath:', this.basePath);
        
        // ベースパスを設定してからEchoを初期化
        this.echo = initializeEcho();
        this.peer = null;
        this.localStream = null;
        this.remoteStream = null;
        this.videoCallId = null;
        this.isInitiator = false;
        
        // DOM要素
        this.localVideo = null;
        this.remoteVideo = null;
        this.callModal = null;
        this.callNotification = null;

        this.setupEventListeners();
    }

    getBasePath() {
        // 現在のパスからベースパスを抽出
        const path = window.location.pathname;
        console.log('Current pathname:', path);
        
        // /mypage または /company を含む場合、その前の部分をベースパスとする
        if (path.includes('/mypage')) {
            const base = path.split('/mypage')[0];
            console.log('Base path (mypage):', base);
            return base;
        } else if (path.includes('/company')) {
            const base = path.split('/company')[0];
            console.log('Base path (company):', base);
            return base;
        }
        
        // フォールバック: パスの最初のセグメントを取得（/bellbi など）
        const segments = path.split('/').filter(s => s);
        if (segments.length > 0 && segments[0] !== 'conversations' && segments[0] !== 'video-calls') {
            const base = '/' + segments[0];
            console.log('Base path (fallback):', base);
            return base;
        }
        
        console.log('Base path (empty):', '');
        return '';
    }

    setupEventListeners() {
        console.log('Setting up event listeners for conversation:', this.conversationId);
        
        try {
            // プライベートチャンネルを購読
            const channel = this.echo.private(`conversation.${this.conversationId}`);
            
            channel.subscribed(() => {
                console.log('Subscribed to conversation channel:', this.conversationId);
            });

            channel.error((error) => {
                console.error('Channel subscription error:', error);
            });

            channel.listen('.video-call.initiated', (data) => {
                console.log('Video call initiated event received:', data);
                if (data.video_call.recipient_id === this.currentUserId) {
                    this.handleIncomingCall(data.video_call);
                }
            })
            .listen('.video-call.accepted', (data) => {
                console.log('Video call accepted event received:', data);
                if (this.isInitiator && this.videoCallId === data.video_call.id) {
                    this.handleCallAccepted();
                }
            })
            .listen('.video-call.declined', (data) => {
                console.log('Video call declined event received:', data);
                if (this.videoCallId === data.video_call.id) {
                    this.handleCallDeclined();
                }
            })
            .listen('.video-call.ended', (data) => {
                console.log('Video call ended event received:', data);
                if (this.videoCallId === data.video_call.id) {
                    this.handleCallEnded();
                }
            })
            .listen('.video-call.signal', (data) => {
                console.log('Video call signal received:', data);
                if (data.sender_id !== this.currentUserId && this.peer) {
                    this.peer.signal(data.signal);
                }
            });
        } catch (error) {
            console.error('Error setting up event listeners:', error);
        }
    }

    async startCall() {
        try {
            console.log('Starting video call...');
            
            // 既存のストリームがあればクリーンアップ
            if (this.localStream) {
                console.log('Cleaning up existing stream...');
                this.localStream.getTracks().forEach(track => track.stop());
                this.localStream = null;
            }
            
            // メディアストリームを取得
            console.log('Requesting user media...');
            this.localStream = await navigator.mediaDevices.getUserMedia({
                video: true,
                audio: true,
            });
            console.log('User media obtained:', this.localStream);

            // ビデオ通話を開始
            console.log('Sending API request to start call...');
            const url = `${this.basePath}/conversations/${this.conversationId}/video-calls`;
            console.log('Request URL:', url, 'Base path:', this.basePath);
            const response = await window.axios.post(url);
            console.log('API response:', response.data);

            this.videoCallId = response.data.video_call.id;
            this.isInitiator = true;

            // モーダルを表示
            this.showCallModal();
            this.setLocalVideo(this.localStream);

            console.log('Video call started successfully. Waiting for recipient to accept...');

        } catch (error) {
            console.error('通話開始エラー:', error);
            console.error('Error details:', {
                name: error.name,
                message: error.message,
                response: error.response,
                stack: error.stack
            });
            
            let errorMessage = 'ビデオ通話を開始できませんでした。';
            if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
                errorMessage = 'カメラとマイクの権限が拒否されました。ブラウザの設定で許可してください。';
            } else if (error.name === 'NotFoundError' || error.name === 'DevicesNotFoundError') {
                errorMessage = 'カメラまたはマイクが見つかりませんでした。';
            } else if (error.name === 'NotReadableError' || error.name === 'TrackStartError') {
                errorMessage = 'カメラまたはマイクが他のアプリケーションで使用中です。他のアプリを閉じてから再度お試しください。';
                // デバイスが使用中の場合は、少し待ってから再試行を提案
                console.log('Device in use, suggesting retry...');
            } else if (error.response) {
                errorMessage = error.response.data?.message || errorMessage;
            }
            
            alert(errorMessage);
            // エラー時もクリーンアップを実行
            this.cleanup();
        }
    }

    async handleIncomingCall(videoCall) {
        this.videoCallId = videoCall.id;
        this.isInitiator = false;
        this.showCallNotification(videoCall);
    }

    async acceptCall() {
        try {
            // 既存のストリームがあればクリーンアップ
            if (this.localStream) {
                console.log('Cleaning up existing stream before accepting call...');
                this.localStream.getTracks().forEach(track => track.stop());
                this.localStream = null;
            }
            
            // メディアストリームを取得
            console.log('Requesting user media for accepting call...');
            this.localStream = await navigator.mediaDevices.getUserMedia({
                video: true,
                audio: true,
            });
            console.log('User media obtained for accepting call:', this.localStream);

            // 通話を受ける
            await window.axios.post(`${this.basePath}/video-calls/${this.videoCallId}/accept`);

            // 通知を非表示
            this.hideCallNotification();

            // モーダルを表示
            this.showCallModal();
            this.setLocalVideo(this.localStream);

            // WebRTC接続を開始
            this.initiatePeerConnection(false);

        } catch (error) {
            console.error('通話受信エラー:', error);
            console.error('Error details:', {
                name: error.name,
                message: error.message
            });
            
            let errorMessage = '通話を受けることができませんでした。';
            if (error.name === 'NotReadableError' || error.name === 'TrackStartError') {
                errorMessage = 'カメラまたはマイクが他のアプリケーションで使用中です。他のアプリを閉じてから再度お試しください。';
            } else if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
                errorMessage = 'カメラとマイクの権限が拒否されました。ブラウザの設定で許可してください。';
            } else if (error.name === 'NotFoundError' || error.name === 'DevicesNotFoundError') {
                errorMessage = 'カメラまたはマイクが見つかりませんでした。';
            }
            
            alert(errorMessage);
            this.cleanup();
        }
    }

    async rejectCall() {
        try {
            await window.axios.post(`${this.basePath}/video-calls/${this.videoCallId}/reject`);
            this.hideCallNotification();
            this.cleanup();
        } catch (error) {
            console.error('通話拒否エラー:', error);
        }
    }

    handleCallAccepted() {
        // WebRTC接続を開始（発信者側）
        this.initiatePeerConnection(true);
    }

    handleCallDeclined() {
        alert('通話が拒否されました。');
        this.cleanup();
    }

    handleCallEnded() {
        alert('通話が終了しました。');
        this.cleanup();
    }

    async endCall() {
        try {
            if (this.videoCallId) {
                await window.axios.post(`${this.basePath}/video-calls/${this.videoCallId}/end`);
            }
            this.cleanup();
        } catch (error) {
            console.error('通話終了エラー:', error);
            // エラーでもクリーンアップは実行
            this.cleanup();
        }
    }

    initiatePeerConnection(isInitiator) {
        const configuration = {
            iceServers: [
                { urls: 'stun:stun.l.google.com:19302' },
            ],
        };

        this.peer = new Peer({
            initiator: isInitiator,
            trickle: false,
            stream: this.localStream,
            config: configuration,
        });

        this.peer.on('signal', (data) => {
            // シグナルをサーバー経由で相手に送信
            window.axios.post(`${this.basePath}/video-calls/${this.videoCallId}/signal`, {
                signal: data,
            });
        });

        this.peer.on('stream', (stream) => {
            this.remoteStream = stream;
            this.setRemoteVideo(stream);
        });

        this.peer.on('error', (err) => {
            console.error('WebRTCエラー:', err);
        });

        this.peer.on('close', () => {
            this.cleanup();
        });
    }

    setLocalVideo(stream) {
        if (this.localVideo) {
            this.localVideo.srcObject = stream;
            this.localVideo.play();
        }
    }

    setRemoteVideo(stream) {
        if (this.remoteVideo) {
            this.remoteVideo.srcObject = stream;
            this.remoteVideo.play();
        }
    }

    showCallModal() {
        if (!this.callModal) {
            this.callModal = document.getElementById('videoCallModal');
            if (!this.callModal) {
                console.error('ビデオ通話モーダルが見つかりません');
                return;
            }
        }

        this.localVideo = this.callModal.querySelector('#localVideo');
        this.remoteVideo = this.callModal.querySelector('#remoteVideo');

        this.callModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    hideCallModal() {
        if (this.callModal) {
            this.callModal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    showCallNotification(videoCall) {
        // 通知UIを表示（実装は後で追加）
        const notification = document.createElement('div');
        notification.id = 'videoCallNotification';
        notification.innerHTML = `
            <div style="position: fixed; top: 20px; right: 20px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 10000;">
                <h3>ビデオ通話が着信しています</h3>
                <p>${videoCall.initiator?.name || '相手'}からの通話</p>
                <button id="acceptCallBtn" style="margin-right: 10px; padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    応答
                </button>
                <button id="rejectCallBtn" style="padding: 10px 20px; background: #f44336; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    拒否
                </button>
            </div>
        `;
        document.body.appendChild(notification);

        document.getElementById('acceptCallBtn').addEventListener('click', () => {
            this.acceptCall();
        });

        document.getElementById('rejectCallBtn').addEventListener('click', () => {
            this.rejectCall();
        });

        this.callNotification = notification;
    }

    hideCallNotification() {
        if (this.callNotification) {
            this.callNotification.remove();
            this.callNotification = null;
        }
    }

    toggleMute() {
        if (this.localStream) {
            const audioTracks = this.localStream.getAudioTracks();
            let isMuted = false;
            audioTracks.forEach(track => {
                track.enabled = !track.enabled;
                if (!track.enabled) {
                    isMuted = true;
                }
            });
            
            // ミュートアイコンを更新
            const muteIcon = document.getElementById('muteIcon');
            if (muteIcon) {
                // 画像のサイズを保持（等倍表示のため）
                const currentWidth = muteIcon.style.width || '24px';
                const currentHeight = muteIcon.style.height || '24px';
                
                if (isMuted) {
                    // ミュート状態: スピーカーオフ画像
                    muteIcon.src = window.SPEAKER_OFF_IMAGE || '/images/スピーカーオフ.png';
                    muteIcon.alt = 'ミュート解除';
                } else {
                    // ミュート解除状態: スピーカー画像
                    muteIcon.src = window.SPEAKER_IMAGE || '/images/スピーカー.png';
                    muteIcon.alt = 'ミュート';
                }
                
                // サイズを確実に設定（等倍表示）
                muteIcon.style.width = currentWidth;
                muteIcon.style.height = currentHeight;
                muteIcon.style.objectFit = 'contain';
                muteIcon.style.objectPosition = 'center';
                
                console.log('Mute toggled, isMuted:', isMuted, 'Icon src:', muteIcon.src);
            }
        }
    }

    toggleVideo() {
        if (this.localStream) {
            const videoTracks = this.localStream.getVideoTracks();
            let isVideoOff = false;
            videoTracks.forEach(track => {
                track.enabled = !track.enabled;
                if (!track.enabled) {
                    isVideoOff = true;
                }
            });
            
            // ビデオアイコンを更新
            const videoIcon = document.getElementById('videoIcon');
            if (videoIcon) {
                // 画像のサイズを保持（等倍表示のため）
                const currentWidth = videoIcon.style.width || '24px';
                const currentHeight = videoIcon.style.height || '24px';
                
                // 画像パスを設定（グローバル変数から取得）
                videoIcon.src = window.CAMERA_IMAGE || '/images/カメラ.png';
                
                // サイズを確実に設定（等倍表示）
                videoIcon.style.width = currentWidth;
                videoIcon.style.height = currentHeight;
                videoIcon.style.objectFit = 'contain';
                videoIcon.style.objectPosition = 'center';
                
                // ビデオOFFの場合は透明度を下げて視覚的に区別
                if (isVideoOff) {
                    videoIcon.style.opacity = '0.5';
                    videoIcon.style.filter = 'grayscale(100%)';
                    videoIcon.alt = 'ビデオON';
                } else {
                    videoIcon.style.opacity = '1';
                    videoIcon.style.filter = 'none';
                    videoIcon.alt = 'ビデオOFF';
                }
                
                console.log('Video toggled, isVideoOff:', isVideoOff, 'Icon src:', videoIcon.src);
            }
        }
    }

    cleanup() {
        console.log('Cleaning up video call resources...');
        
        // WebRTC接続を閉じる
        if (this.peer) {
            try {
                this.peer.destroy();
            } catch (error) {
                console.error('Error destroying peer:', error);
            }
            this.peer = null;
        }

        // ローカルストリームを停止
        if (this.localStream) {
            try {
                this.localStream.getTracks().forEach(track => {
                    track.stop();
                    console.log('Stopped track:', track.kind, track.label);
                });
            } catch (error) {
                console.error('Error stopping local stream tracks:', error);
            }
            this.localStream = null;
        }

        // リモートストリームをクリア
        if (this.remoteStream) {
            try {
                this.remoteStream.getTracks().forEach(track => {
                    track.stop();
                    console.log('Stopped remote track:', track.kind, track.label);
                });
            } catch (error) {
                console.error('Error stopping remote stream tracks:', error);
            }
            this.remoteStream = null;
        }

        // ビデオ要素をクリア
        if (this.localVideo) {
            try {
                this.localVideo.srcObject = null;
                this.localVideo.pause();
            } catch (error) {
                console.error('Error clearing local video:', error);
            }
        }
        if (this.remoteVideo) {
            try {
                this.remoteVideo.srcObject = null;
                this.remoteVideo.pause();
            } catch (error) {
                console.error('Error clearing remote video:', error);
            }
        }

        // UIを非表示
        this.hideCallModal();
        this.hideCallNotification();

        // 状態をリセット
        this.videoCallId = null;
        this.isInitiator = false;
        
        console.log('Cleanup completed');
    }
}

// グローバルにエクスポート
window.VideoCallManager = VideoCallManager;

export default VideoCallManager;

