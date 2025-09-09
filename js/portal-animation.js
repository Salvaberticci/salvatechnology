import * as THREE from 'three';

let portal;

export function createPortal(scene) {
    const geometry = new THREE.TorusGeometry(1, 0.2, 16, 100);
    const material = new THREE.MeshStandardMaterial({ 
        color: 0xff8c00, 
        emissive: 0xff8c00,
        emissiveIntensity: 1,
        wireframe: true 
    });
    portal = new THREE.Mesh(geometry, material);
    portal.scale.set(0, 0, 0);
    portal.position.z = 2;
    scene.add(portal);
}

export function openPortal(onComplete) {
    if (!portal) return;

    const tl = gsap.timeline({ onComplete });
    
    tl.to(portal.scale, {
        x: 1,
        y: 1,
        z: 1,
        duration: 1.5,
        ease: 'power2.inOut'
    })
    .to(portal.rotation, {
        z: Math.PI * 2,
        duration: 2,
        ease: 'none',
        repeat: -1
    }, 0)
    .to(portal.scale, {
        x: 0,
        y: 0,
        z: 0,
        duration: 1,
        delay: 1,
        ease: 'power2.in'
    });
}
