// VideoCallManagerクラス
class VideoCallManager {
    constructor(conversationId, currentUserId, basePath = '') {
        this.conversationId = conversationId;
        this.currentUserId = currentUserId;
        this.basePath = basePath;
        this.localStream = null;
        this.remoteStream = null;
        this.peerConnection = null;
        this.isVideoEnabled = true;
        this.isAudioEnabled = true;
        this.videoCallId = null;
        this.remoteVideoCheckInterval = null;
        
        // DOM要素
        this.modal = document.getElementById('videoCallModal');
        this.localVideo = document.getElementById('localVideo');
        this.remoteVideo = document.getElementById('remoteVideo');
        this.videoIcon = document.getElementById('videoIcon');
        this.muteIcon = document.getElementById('muteIcon');
        
        // カメラオフ表示用の要素を作成（DOM要素が存在する場合のみ）
        if (this.modal) {
            this.createCameraOffOverlays();
        }
    }
    
    createCameraOffOverlays() {
        // 既にオーバーレイが存在する場合は削除して再作成
        const existingLocal = document.getElementById('localVideoOffOverlay');
        const existingRemote = document.getElementById('remoteVideoOffOverlay');
        if (existingLocal) existingLocal.remove();
        if (existingRemote) existingRemote.remove();
        
        // ローカルビデオ用の「カメラオフ」表示
        const localOverlay = document.createElement('div');
        localOverlay.id = 'localVideoOffOverlay';
        localOverlay.innerHTML = '<span style="font-size: 16px; font-weight: 500;">カメラオフ</span>';
        localOverlay.style.cssText = `
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 200px;
            height: 150px;
            display: none;
            align-items: center;
            justify-content: center;
            background: #1a1a1a;
            border: 2px solid #fff;
            border-radius: 8px;
            color: #ffffff;
            font-size: 16px;
            font-weight: 500;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
            z-index: 10003;
            pointer-events: none;
            visibility: hidden;
        `;
        
        // リモートビデオ用の「カメラオフ」表示
        const remoteOverlay = document.createElement('div');
        remoteOverlay.id = 'remoteVideoOffOverlay';
        remoteOverlay.innerHTML = '<span style="font-size: 24px; font-weight: 500;">カメラオフ</span>';
        remoteOverlay.style.cssText = `
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: none;
            align-items: center;
            justify-content: center;
            background: #1a1a1a;
            color: #ffffff;
            font-size: 24px;
            font-weight: 500;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
            padding: 20px 40px;
            border-radius: 8px;
            z-index: 10003;
            pointer-events: none;
            visibility: hidden;
        `;
        
        // モーダルに追加
        if (this.modal) {
            // ビデオコンテナを探す（position: relativeのdiv）
            const videoContainer = this.modal.querySelector('div[style*="position: relative"]');
            if (videoContainer) {
                // ビデオコンテナに追加（position: relativeなので、absoluteのオーバーレイが正しく配置される）
                videoContainer.appendChild(localOverlay);
                videoContainer.appendChild(remoteOverlay);
                
                // ローカルビデオの位置を確認して、オーバーレイの位置を調整
                if (this.localVideo) {
                    const rect = this.localVideo.getBoundingClientRect();
                    console.log('Local video position:', rect);
                }
                
                console.log('Camera off overlays created in video container', {
                    localOverlay: localOverlay,
                    remoteOverlay: remoteOverlay,
                    videoContainer: videoContainer,
                    localVideo: this.localVideo
                });
            } else {
                // フォールバック: モーダルに直接追加
                this.modal.appendChild(localOverlay);
                this.modal.appendChild(remoteOverlay);
                console.log('Camera off overlays created in modal (fallback)');
            }
        } else {
            console.error('Modal not found when creating overlays');
        }
    }
    
    updateLocalVideoOverlay() {
        // オーバーレイが存在しない場合は作成
        let overlay = document.getElementById('localVideoOffOverlay');
        if (!overlay) {
            if (this.modal) {
                this.createCameraOffOverlays();
                overlay = document.getElementById('localVideoOffOverlay');
            }
            if (!overlay) {
                console.error('Failed to create local video overlay');
                return;
            }
        }
        
        if (this.localVideo) {
            if (!this.isVideoEnabled) {
                // オーバーレイを強制的に表示
                overlay.style.cssText = `
                    position: absolute;
                    bottom: 20px;
                    right: 20px;
                    width: 200px;
                    height: 150px;
                    display: flex !important;
                    align-items: center;
                    justify-content: center;
                    background: #1a1a1a;
                    border: 2px solid #fff;
                    border-radius: 8px;
                    color: #ffffff;
                    font-size: 16px;
                    font-weight: 500;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                    z-index: 10003 !important;
                    pointer-events: none;
                    visibility: visible !important;
                    opacity: 1 !important;
                `;
                
                // ビデオ要素を非表示
                this.localVideo.style.opacity = '0';
                this.localVideo.style.pointerEvents = 'none';
                
                console.log('Local video overlay displayed', {
                    overlay: overlay,
                    overlayDisplay: window.getComputedStyle(overlay).display,
                    overlayVisibility: window.getComputedStyle(overlay).visibility,
                    overlayZIndex: window.getComputedStyle(overlay).zIndex,
                    videoOpacity: this.localVideo.style.opacity,
                    isVideoEnabled: this.isVideoEnabled
                });
            } else {
                overlay.style.display = 'none';
                overlay.style.visibility = 'hidden';
                this.localVideo.style.opacity = '1';
                this.localVideo.style.pointerEvents = 'auto';
            }
        } else {
            console.error('Local video element not found');
        }
    }
    
    updateRemoteVideoOverlay() {
        const overlay = document.getElementById('remoteVideoOffOverlay');
        if (overlay && this.remoteVideo) {
            // リモートビデオのストリームがない、またはビデオトラックがない場合
            const hasVideoTrack = this.remoteVideo.srcObject && 
                this.remoteVideo.srcObject.getVideoTracks().length > 0 &&
                this.remoteVideo.srcObject.getVideoTracks()[0].enabled;
            
            if (!hasVideoTrack) {
                overlay.style.display = 'flex';
                // ビデオ要素は非表示にせず、オーバーレイを上に表示
                if (this.remoteVideo) {
                    this.remoteVideo.style.opacity = '0';
                    this.remoteVideo.style.pointerEvents = 'none';
                }
            } else {
                overlay.style.display = 'none';
                if (this.remoteVideo) {
                    this.remoteVideo.style.opacity = '1';
                    this.remoteVideo.style.pointerEvents = 'auto';
                }
            }
        }
    }
    
    // リモートストリームを設定（WebRTCのonaddstreamまたはontrackから呼ばれる）
    setRemoteStream(stream) {
        this.remoteStream = stream;
        
        if (this.remoteVideo) {
            this.remoteVideo.srcObject = stream;
            
            // ストリームのトラックの状態を監視
            if (stream) {
                stream.getVideoTracks().forEach(track => {
                    track.onended = () => {
                        this.updateRemoteVideoOverlay();
                    };
                    track.onmute = () => {
                        this.updateRemoteVideoOverlay();
                    };
                    track.onunmute = () => {
                        this.updateRemoteVideoOverlay();
                    };
                });
            }
            
            // ビデオ要素のイベントも監視
            this.remoteVideo.addEventListener('loadedmetadata', () => {
                this.updateRemoteVideoOverlay();
            });
        }
        
        this.updateRemoteVideoOverlay();
    }
    
    async startCall() {
        try {
            // ビデオ通話を開始するAPIを呼び出す
            const response = await axios.post(`${this.basePath}/conversations/${this.conversationId}/video-calls`);
            this.videoCallId = response.data.video_call_id;
            
            // モーダルを表示
            if (this.modal) {
                this.modal.style.display = 'flex';
                
                // モーダル表示後にオーバーレイを作成（まだ作成されていない場合）
                if (!document.getElementById('localVideoOffOverlay')) {
                    this.createCameraOffOverlays();
                }
            }
            
            // ローカルストリームを取得
            await this.getLocalStream();
            
            // リモートビデオの状態を定期的にチェック
            this.startRemoteVideoCheck();
            
            // WebRTCの設定（簡易実装）
            // 実際の実装では、シグナリングサーバーやSTUN/TURNサーバーが必要
            console.log('Video call started:', this.videoCallId);
        } catch (error) {
            console.error('Failed to start video call:', error);
            alert('ビデオ通話の開始に失敗しました。');
        }
    }
    
    async getLocalStream() {
        try {
            this.localStream = await navigator.mediaDevices.getUserMedia({
                video: this.isVideoEnabled,
                audio: this.isAudioEnabled
            });
            
            if (this.localVideo) {
                this.localVideo.srcObject = this.localStream;
            }
            
            this.updateLocalVideoOverlay();
        } catch (error) {
            console.error('Failed to get local stream:', error);
            alert('カメラまたはマイクへのアクセスに失敗しました。');
        }
    }
    
    toggleVideo() {
        this.isVideoEnabled = !this.isVideoEnabled;
        console.log('Toggle video called, isVideoEnabled:', this.isVideoEnabled);
        
        if (this.localStream) {
            const videoTracks = this.localStream.getVideoTracks();
            videoTracks.forEach(track => {
                track.enabled = this.isVideoEnabled;
            });
        }
        
        // アイコンの更新（カメラオフ用のアイコンがあれば使用）
        if (this.videoIcon) {
            // アイコンの切り替えは既存の実装に従う
            // 必要に応じてカメラオフ用のアイコンを設定
        }
        
        // オーバーレイを即座に更新
        this.updateLocalVideoOverlay();
        
        // 念のため、少し遅延させてもう一度更新
        setTimeout(() => {
            this.updateLocalVideoOverlay();
        }, 50);
    }
    
    toggleMute() {
        this.isAudioEnabled = !this.isAudioEnabled;
        
        if (this.localStream) {
            const audioTracks = this.localStream.getAudioTracks();
            audioTracks.forEach(track => {
                track.enabled = this.isAudioEnabled;
            });
        }
        
        // ミュートアイコンの更新
        if (this.muteIcon && window.SPEAKER_OFF_IMAGE && window.SPEAKER_IMAGE) {
            this.muteIcon.src = this.isAudioEnabled ? window.SPEAKER_IMAGE : window.SPEAKER_OFF_IMAGE;
        }
    }
    
    startRemoteVideoCheck() {
        // リモートビデオの状態を定期的にチェック（1秒ごと）
        this.remoteVideoCheckInterval = setInterval(() => {
            this.updateRemoteVideoOverlay();
        }, 1000);
    }
    
    stopRemoteVideoCheck() {
        if (this.remoteVideoCheckInterval) {
            clearInterval(this.remoteVideoCheckInterval);
            this.remoteVideoCheckInterval = null;
        }
    }
    
    endCall() {
        // リモートビデオチェックを停止
        this.stopRemoteVideoCheck();
        
        // ストリームを停止
        if (this.localStream) {
            this.localStream.getTracks().forEach(track => track.stop());
            this.localStream = null;
        }
        
        if (this.remoteStream) {
            this.remoteStream.getTracks().forEach(track => track.stop());
            this.remoteStream = null;
        }
        
        // ビデオ要素をクリア
        if (this.localVideo) {
            this.localVideo.srcObject = null;
            this.localVideo.style.display = 'block';
            this.localVideo.style.opacity = '1';
            this.localVideo.style.pointerEvents = 'auto';
        }
        
        if (this.remoteVideo) {
            this.remoteVideo.srcObject = null;
            this.remoteVideo.style.display = 'block';
            this.remoteVideo.style.opacity = '1';
            this.remoteVideo.style.pointerEvents = 'auto';
        }
        
        // オーバーレイを非表示
        const localOverlay = document.getElementById('localVideoOffOverlay');
        const remoteOverlay = document.getElementById('remoteVideoOffOverlay');
        if (localOverlay) localOverlay.style.display = 'none';
        if (remoteOverlay) remoteOverlay.style.display = 'none';
        
        // モーダルを非表示
        if (this.modal) {
            this.modal.style.display = 'none';
        }
        
        // ビデオ通話終了のイベントを送信（Pusher経由など）
        // 実際の実装では、ビデオ通話終了のAPIを呼び出す
        console.log('Video call ended');
    }
}

// グローバルに公開
window.VideoCallManager = VideoCallManager;
