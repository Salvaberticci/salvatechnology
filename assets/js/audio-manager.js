class AudioManager {
    constructor() {
        this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
        this.sounds = {};
        this.unlocked = false;
    }

    unlock() {
        if (this.unlocked) return;
        this.audioContext.resume().then(() => {
            this.unlocked = true;
            console.log('Audio unlocked');
            // Play any sounds that were queued
            if (this.sounds['load']) {
                this.playSound('load');
            }
        });
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
            console.log('Audio not unlocked yet. Sound will play after user interaction.');
        }
    }
}

const audioManager = new AudioManager();
audioManager.loadSound('load', 'sounds/future-high-tech-logo-158838.mp3');
audioManager.loadSound('glitch', 'sounds/glitch.mp3');
