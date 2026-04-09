<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Direktorat Metrologi | Sistem Informasi 3D</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/BPSUML2.png') }}">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            overflow: hidden;
            background: #050b1a; /* fallback color */
            color: white;
        }

        .delay-appear {
            opacity: 0;
            animation: fadeSlideUp 0.8s ease-out forwards;
            animation-delay: 2s; 
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Canvas 3D di belakang semua konten */
        #canvas-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            display: block;
        }

        /* Semua konten utama di atas canvas */
        .content {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Logo Pill - glassmorphism yang lebih tajam */
        .logo-pill {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(15, 25, 45, 0.55);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(56, 189, 248, 0.35);
            border-radius: 60px;
            padding: 8px 20px 8px 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .logo-pill:hover {
            border-color: rgba(56, 189, 248, 0.7);
            background: rgba(15, 25, 45, 0.75);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        }

        .logo-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #38bdf8, #0284c7);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(56, 189, 248, 0.5);
            flex-shrink: 0;
        }

        /* Glass Card utama - lebih elegan */
        .glass-card {
            background: rgba(10, 20, 40, 0.55);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(56, 189, 248, 0.3);
            border-radius: 32px;
            padding: 42px 40px 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            text-align: center;
            width: 100%;
            max-width: 440px;
            transition: transform 0.3s ease, border-color 0.3s ease;
        }

        .glass-card:hover {
            border-color: rgba(56, 189, 248, 0.7);
            transform: translateY(-5px);
        }

        .icon-wrap {
            width: 90px;
            height: 90px;
            background: rgba(56, 189, 248, 0.12);
            border: 1px solid rgba(56, 189, 248, 0.4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            box-shadow: 0 0 35px rgba(56, 189, 248, 0.25);
            transition: all 0.3s ease;
        }

        .icon-wrap img {
            width: 55px;
            height: auto;
            filter: drop-shadow(0 0 6px rgba(56, 189, 248, 0.6));
        }

        .divider-glow {
            width: 60px;
            height: 2px;
            background: linear-gradient(90deg, transparent, #38bdf8, #0ea5e9, transparent);
            margin: 28px auto 28px;
            border-radius: 2px;
        }

        .btn-primary {
            display: block;
            width: 100%;
            padding: 14px 20px;
            border-radius: 40px;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            text-align: center;
            letter-spacing: 0.3px;
            background: linear-gradient(105deg, #38bdf8 0%, #0284c7 100%);
            color: white;
            box-shadow: 0 8px 20px rgba(56, 189, 248, 0.4), 0 1px 0 rgba(255, 255, 255, 0.2) inset;
            margin-bottom: 14px;
            transition: all 0.25s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(56, 189, 248, 0.6);
            background: linear-gradient(105deg, #4fc3ff 0%, #0e7fcb 100%);
        }

        .btn-secondary {
            display: block;
            width: 100%;
            padding: 14px 20px;
            border-radius: 40px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            background: rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(56, 189, 248, 0.5);
            backdrop-filter: blur(4px);
            transition: all 0.25s ease;
        }

        .btn-secondary:hover {
            transform: translateY(-3px);
            background: rgba(56, 189, 248, 0.2);
            border-color: rgba(56, 189, 248, 0.9);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        /* Animasi subtle untuk teks */
        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-text {
            animation: fadeSlideUp 0.8s ease-out forwards;
        }
    </style>
</head>
<body>
    <div id="canvas-container"></div>

    <div class="content">
        <div class="flex justify-end items-center py-6 px-8">
            <div class="logo-pill">
                <div class="logo-icon">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h2 style="font-size:14px; font-weight:700; color:#fff; letter-spacing:-0.2px;">Balai Pengelola SUML</h2>
                    <p style="font-size:10px; color:rgba(255,255,255,0.65); margin-top:2px;"> BPSUML Data Pengelolaan</p>
                </div>
            </div>
        </div>

        <!-- Main Content: Card -->
        <div class="flex-1 flex items-center justify-center px-4 pb-12 pt-6">
            <div class="glass-card delay-appear">
                <!-- Logo Image -->
                <div class="icon-wrap">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('images/BPSUML2.png') }}" alt="Logo Metrologi">
                    </a>
                </div>

                <h1 style="font-size:30px; font-weight:800; color:#fff; letter-spacing:-0.02em; margin-bottom:10px; background: linear-gradient(135deg, #fff, #a5f3ff); -webkit-background-clip: text; background-clip: text; color: transparent;">
                    Selamat Datang
                </h1>
                <p style="font-size:14px; color:rgba(255,255,255,0.7); margin-bottom:20px; line-height:1.5; font-weight:500;">
                    Sistem Informasi Balai Pengelola SUML
                </p>

                <div class="divider-glow"></div>

                <!-- Action Buttons -->
                <a href="{{ route('login') }}" class="btn-primary">Login ke Dashboard</a>
                <a href="{{ route('register') }}" class="btn-secondary">Buat Akun Baru</a>

                <p style="font-size:11px; color:rgba(255,255,255,0.4); margin-top:32px; letter-spacing:0.3px; font-weight:500;">
                    &copy; {{ date('Y') }} Balai Pengelola SUML. Hak Cipta Dilindungi.
                </p>
            </div>
        </div>
    </div>

    <!-- Three.js Library -->
    <script type="importmap">
        {
            "imports": {
                "three": "https://unpkg.com/three@0.128.0/build/three.module.js"
            }
        }
    </script>

    <script type="module">
        import * as THREE from 'three';

        // --- Inisialisasi Scene, Camera, Renderer ---
        const container = document.getElementById('canvas-container');
        const scene = new THREE.Scene();
        scene.background = new THREE.Color(0x050b1a); // deep space blue-black
        scene.fog = new THREE.FogExp2(0x050b1a, 0.008); // fog untuk kedalaman

        // Camera dengan sudut pandang lebar
        const camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 1000);
        camera.position.set(0, 1.5, 12);
        camera.lookAt(0, 0, 0);

        const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: false });
        renderer.setSize(window.innerWidth, window.innerHeight);
        renderer.setPixelRatio(window.devicePixelRatio);
        renderer.shadowMap.enabled = true; // shadow untuk efek realistis
        container.appendChild(renderer.domElement);

        // --- Lighting System yang Dramatis ---
        // Ambient light
        const ambientLight = new THREE.AmbientLight(0x1a2a4a, 0.55);
        scene.add(ambientLight);
        
        // Main directional light (bayangan)
        const mainLight = new THREE.DirectionalLight(0xffffff, 1.2);
        mainLight.position.set(3, 5, 2);
        mainLight.castShadow = true;
        mainLight.receiveShadow = false;
        mainLight.shadow.mapSize.width = 1024;
        mainLight.shadow.mapSize.height = 1024;
        scene.add(mainLight);
        
        // Back rim light (warna biru)
        const backLight = new THREE.PointLight(0x38bdf8, 0.8);
        backLight.position.set(-2, 1, -4);
        scene.add(backLight);
        
        // Fill light dari bawah (warna cyan)
        const fillLight = new THREE.PointLight(0x0ea5e9, 0.6);
        fillLight.position.set(1, -2, 2);
        scene.add(fillLight);
        
        // Dynamic colored lights (berputar)
        const colorLight1 = new THREE.PointLight(0xff4d4d, 0.5);
        colorLight1.position.set(2, 1, 3);
        scene.add(colorLight1);
        
        const colorLight2 = new THREE.PointLight(0x4dffb5, 0.5);
        colorLight2.position.set(-2, 1.5, 3.5);
        scene.add(colorLight2);
        
        const knotGeometry = new THREE.TorusKnotGeometry(1.1, 0.28, 180, 24, 3, 4);
        const knotMaterial = new THREE.MeshStandardMaterial({
            color: 0x3b82f6,
            emissive: 0x0a3366,
            roughness: 0.25,
            metalness: 0.85,
            emissiveIntensity: 0.7,
            flatShading: false
        });
        const torusKnot = new THREE.Mesh(knotGeometry, knotMaterial);
        torusKnot.castShadow = true;
        torusKnot.receiveShadow = false;
        scene.add(torusKnot);
        
        const ringGeometry = new THREE.TorusGeometry(1.45, 0.05, 128, 200);
        const ringMaterial = new THREE.MeshStandardMaterial({
            color: 0x38bdf8,
            emissive: 0x0284c7,
            emissiveIntensity: 0.5,
            metalness: 0.9,
            roughness: 0.3
        });
        const outerRing = new THREE.Mesh(ringGeometry, ringMaterial);
        outerRing.rotation.x = Math.PI / 2;
        scene.add(outerRing);
        
        // Tambahkan inner glowing sphere (transparan)
        const coreGlowMat = new THREE.MeshPhongMaterial({
            color: 0x0ea5e9,
            emissive: 0x0c4e6e,
            transparent: true,
            opacity: 0.15,
            side: THREE.BackSide
        });
        const innerSphere = new THREE.Mesh(new THREE.SphereGeometry(0.85, 32, 32), coreGlowMat);
        scene.add(innerSphere);
        
        // --- Particle System: Ribuan bintang 3D dengan warna biru/cyan ---
        const particleCount = 3500;
        const particlesGeometry = new THREE.BufferGeometry();
        const positions = new Float32Array(particleCount * 3);
        const colors = new Float32Array(particleCount * 3);
        
        for (let i = 0; i < particleCount; i++) {
            // Distribusi dalam bentuk sphere dan sedikit elliptical
            const radius = 5 + Math.random() * 4;
            const theta = Math.random() * Math.PI * 2;
            const phi = Math.acos(2 * Math.random() - 1);
            
            const x = radius * Math.sin(phi) * Math.cos(theta);
            const y = radius * Math.sin(phi) * Math.sin(theta) * 0.8;
            const z = radius * Math.cos(phi) * 1.2;
            
            positions[i*3] = x;
            positions[i*3+1] = y;
            positions[i*3+2] = z;
            
            // Warna partikel: gradasi biru, cyan, putih kebiruan
            const colorChoice = Math.random();
            if (colorChoice < 0.6) {
                colors[i*3] = 0.2 + Math.random() * 0.4;   // R
                colors[i*3+1] = 0.5 + Math.random() * 0.5; // G
                colors[i*3+2] = 0.8 + Math.random() * 0.2; // B
            } else if (colorChoice < 0.85) {
                colors[i*3] = 0.1 + Math.random() * 0.3;
                colors[i*3+1] = 0.6 + Math.random() * 0.4;
                colors[i*3+2] = 1.0;
            } else {
                colors[i*3] = 0.7 + Math.random() * 0.3;
                colors[i*3+1] = 0.8 + Math.random() * 0.2;
                colors[i*3+2] = 1.0;
            }
        }
        
        particlesGeometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        particlesGeometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));
        
        const particleMaterial = new THREE.PointsMaterial({
            size: 0.07,
            vertexColors: true,
            blending: THREE.AdditiveBlending,
            transparent: true,
            opacity: 0.9
        });
        
        const particleSystem = new THREE.Points(particlesGeometry, particleMaterial);
        scene.add(particleSystem);
        
        // Tambahkan floating small stars di latar belakang lebih banyak
        const starCount = 1800;
        const starGeo = new THREE.BufferGeometry();
        const starPos = [];
        for (let i = 0; i < starCount; i++) {
            starPos.push((Math.random() - 0.5) * 200);
            starPos.push((Math.random() - 0.5) * 100);
            starPos.push((Math.random() - 0.5) * 60 - 30);
        }
        starGeo.setAttribute('position', new THREE.BufferAttribute(new Float32Array(starPos), 3));
        const starMat = new THREE.PointsMaterial({ color: 0xaaddff, size: 0.05, transparent: true, opacity: 0.6, blending: THREE.AdditiveBlending });
        const starsField = new THREE.Points(starGeo, starMat);
        scene.add(starsField);
        
        // --- Efek Floating Light Orbs (berputar) ---
        const orbGroup = new THREE.Group();
        const orbCount = 12;
        const orbMeshes = [];
        for (let i = 0; i < orbCount; i++) {
            const orbGeo = new THREE.SphereGeometry(0.09, 8, 8);
            const orbMat = new THREE.MeshStandardMaterial({
                color: 0x38bdf8,
                emissive: 0x0ea5e9,
                emissiveIntensity: 0.9,
                metalness: 0.2
            });
            const orb = new THREE.Mesh(orbGeo, orbMat);
            const angle = (i / orbCount) * Math.PI * 2;
            const radiusOrb = 2.2;
            orb.position.x = Math.cos(angle) * radiusOrb;
            orb.position.z = Math.sin(angle) * radiusOrb;
            orb.position.y = Math.sin(angle * 2) * 0.8;
            orbGroup.add(orb);
            orbMeshes.push(orb);
        }
        scene.add(orbGroup);
        
        // Tambahan beberapa garis wireframe elegan di sekitar
        const wireframeSphereGeo = new THREE.SphereGeometry(1.65, 24, 18);
        const wireframeMat = new THREE.MeshBasicMaterial({ color: 0x2c6e9e, wireframe: true, transparent: true, opacity: 0.12 });
        const wireframeSphere = new THREE.Mesh(wireframeSphereGeo, wireframeMat);
        scene.add(wireframeSphere);
        
        // --- Mouse Interactivity (Parallax 3D) ---
        let mouseX = 0;
        let mouseY = 0;
        let targetRotationX = 0;
        let targetRotationY = 0;
        
        window.addEventListener('mousemove', (event) => {
            mouseX = (event.clientX / window.innerWidth) * 2 - 1;
            mouseY = (event.clientY / window.innerHeight) * 2 - 1;
            targetRotationY = mouseX * 2;
            targetRotationX = mouseY * 1.6;
        });
        
        // --- Animasi & Rotasi ---
        let time = 0;
        
        function animate() {
            requestAnimationFrame(animate);
            time += 0.008;
            
            // Rotasi utama torus knot dan ring
            torusKnot.rotation.x += 0.005;
            torusKnot.rotation.y += 0.007;
            torusKnot.rotation.z += 0.003;
            
            outerRing.rotation.z += 0.003;
            outerRing.rotation.y += 0.002;
            
            wireframeSphere.rotation.x += 0.001;
            wireframeSphere.rotation.y += 0.002;
            
            // Particle system slow rotation
            particleSystem.rotation.y += 0.0005;
            particleSystem.rotation.x += 0.0003;
            starsField.rotation.y -= 0.0002;
            
            orbGroup.rotation.y += 0.008;
            orbGroup.rotation.x = Math.sin(time * 0.5) * 0.1;
            orbGroup.rotation.z = Math.cos(time * 0.7) * 0.05;
            
            const hue1 = (time * 0.3) % (Math.PI * 2);
            colorLight1.intensity = 0.55 + Math.sin(time) * 0.2;
            colorLight2.intensity = 0.55 + Math.cos(time * 0.9) * 0.2;
            colorLight1.color.setHSL(0.55 + Math.sin(time * 0.7) * 0.1, 1, 0.6);
            colorLight2.color.setHSL(0.45 + Math.cos(time * 0.5) * 0.1, 1, 0.6);
            
            
            const lerpSpeed = 0.05;
            camera.position.x += (targetRotationY * 0.8 - camera.position.x) * lerpSpeed;
            camera.position.y += (-targetRotationX * 0.5 - camera.position.y) * lerpSpeed;
            camera.lookAt(0, 0.2, 0);
            
            renderer.render(scene, camera);
        }
        
        animate();
        
        window.addEventListener('resize', onWindowResize, false);
        function onWindowResize() {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        }
        
        // Sedikit efek zoom halus ketika load (opsional)
        setTimeout(() => {
            camera.position.set(0, 1.2, 11);
        }, 100);
    </script>
</body>
</html>