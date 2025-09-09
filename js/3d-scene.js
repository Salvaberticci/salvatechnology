import * as THREE from 'three';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
import { createPortal, openPortal as openPortalAnimation } from './portal-animation.js';

// --- SCENE SETUP ---
export const scene = new THREE.Scene();
export const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
export const renderer = new THREE.WebGLRenderer({
    canvas: document.querySelector('#bg'),
    alpha: true
});
renderer.setPixelRatio(window.devicePixelRatio);
renderer.setSize(window.innerWidth, window.innerHeight);
camera.position.z = 5;

createPortal(scene);

export const openPortal = (onComplete) => {
    openPortalAnimation(onComplete);
};

// --- LIGHTS ---
const ambientLight = new THREE.AmbientLight(0xffffff, 0.2);
scene.add(ambientLight);

const directionalLight = new THREE.DirectionalLight(0xffffff, 1);
directionalLight.position.set(5, 5, 5);
scene.add(directionalLight);

const orangeLight = new THREE.PointLight(0xff8c00, 2, 50);
orangeLight.position.set(0, 2, 2);
scene.add(orangeLight);

// --- BACKGROUND CODE ---
const particleCount = 1500;
const particles = new THREE.Group();

const material = new THREE.SpriteMaterial({
    color: 0xff8c00,
    transparent: true,
    opacity: 0.8,
    blending: THREE.AdditiveBlending
});

for (let i = 0; i < particleCount; i++) {
    const canvas = document.createElement('canvas');
    const context = canvas.getContext('2d');
    canvas.width = 32;
    canvas.height = 32;
    context.font = 'bold 32px monospace';
    context.fillStyle = '#ff8c00';
    context.textAlign = 'center';
    context.textBaseline = 'middle';
    context.fillText(Math.random() > 0.5 ? '1' : '0', 16, 16);
    
    const texture = new THREE.CanvasTexture(canvas);
    const spriteMaterial = material.clone();
    spriteMaterial.map = texture;

    const sprite = new THREE.Sprite(spriteMaterial);
    sprite.position.set(
        (Math.random() - 0.5) * 40,
        (Math.random() - 0.5) * 40,
        (Math.random() - 0.5) * 40
    );
    sprite.scale.set(0.2, 0.2, 0.2);
    particles.add(sprite);
}
scene.add(particles);


// --- 3D OBJECT ---
const loader = new GLTFLoader();
let model;

loader.load(
    '3d/salva.glb',
    function (gltf) {
        model = gltf.scene;
        model.scale.set(6, 6, 6);
        model.position.y = -1;
        scene.add(model);
    },
    undefined,
    function (error) {
        console.error(error);
    }
);

// --- MOUSE INTERACTION ---
const mouse = new THREE.Vector2();
function onMouseMove(event) {
    mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
    mouse.y = -(event.clientY / window.innerHeight) * 2 + 1;
}
window.addEventListener('mousemove', onMouseMove, false);

// --- ANIMATION LOOP ---
function animate() {
    requestAnimationFrame(animate);

    particles.children.forEach(p => {
        p.position.y -= 0.02;
        if (p.position.y < -20) {
            p.position.y = 20;
        }
    });

    if (model) {
        const modelTime = Date.now() * 0.001;
        model.rotation.y = modelTime * 0.3;

        // Mouse movement effect
        model.rotation.y += (mouse.x * 0.5 - model.rotation.y) * 0.1;
        model.rotation.x += (-mouse.y * 0.5 - model.rotation.x) * 0.1;
    }

    renderer.render(scene, camera);
}
animate();

// --- RESIZE HANDLER ---
function onWindowResize() {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
}
window.addEventListener('resize', onWindowResize, false);
