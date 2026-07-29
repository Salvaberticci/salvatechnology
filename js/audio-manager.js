class AudioManager {
    constructor() {
        this.audioContext = null;
        this.sounds = {};
        this.unlocked = false;
    }

    _ensureContext() {
        if (!this.audioContext) {
            try {
                this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
            } catch (e) {
                console.warn('AudioContext no disponible:', e);
            }
        }
    }

    unlock() {
        if (this.unlocked) return;
        this._ensureContext();
        if (this.audioContext) {
            this.audioContext.resume().then(() => {
                this.unlocked = true;
                if (this.sounds['load']) {
                    this.playSound('load');
                }
            }).catch(() => {});
        } else {
            this.unlocked = true;
        }
    }

    loadSound(name, src, loop = false) {
        const sound = new Audio(src);
        sound.loop = loop;
        this.sounds[name] = sound;
    }

    playSound(name) {
        if (!this.sounds[name]) return;

        if (this.unlocked) {
            this.sounds[name].currentTime = 0;
            this.sounds[name].play();
        } else {
            console.log('Audio no desbloqueado. Sonará después de interacción del usuario.');
        }
    }
}

const audioManager = new AudioManager();
audioManager.loadSound('load', 'sounds/future-high-tech-logo-158838.mp3');
audioManager.loadSound('glitch', 'sounds/glitch.mp3');
