<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
    <title>Carrito</title>
    <style>
        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            margin: 5px;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            background-color: #f9f9f9;
        }
        
        .container {
            width: 90%;
            max-width: 1000px;
            text-align: center;
        }
        
        .card {
            background: #ffffff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            margin: 10px auto;
        }
        
        .label {
            display: block;
            font-size: 14px;
            color: #333;
            margin: 8px 0 4px 0;
        }
        
        .input {
            width: 100px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 12px;
            background: #f9f9f9;
            transition: border 0.3s;
        }
        
        .input:focus {
            border-color: #ff7f7f;
            outline: none;
            background: #fff;
        }
        
        button {
            width: 100px;
            padding: 6px;
            color: white;
            background: #ff7f7f;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        
        button:hover {
            background: #ff4d4d;
        }
        
        .content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }
        
        h3 {
            font-size: 18px;
            text-align: center;
            color: #ff7f7f;
            margin-bottom: 10px;
        }
        
        canvas {
            width: 100%;
            height: 600px;
            max-width: 950px;
            border: 1px solid #ccc;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div id="simulacion" class="container center">
        <h3>Simulación de Carrito Diferencial</h3>
        Motor Simulado (DC RS-775 12V)
        <div class="card">
            <div class="content">
                <label class="label">Voltaje Motor Izquierdo (V)</label>
                <input type="number" id="voltajeIzquierdo" class="input" min="0" max="12" value="0">
                
                <label class="label">Voltaje Motor Derecho (V)</label>
                <input type="number" id="voltajeDerecho" class="input" min="0" max="12" value="0">
            </div>
            <div class="container mt-5">
                <h3 style="color: #ff7f7f;">Simulación Gráfica del Vehículo</h3>
            
                <div class="card p-4 my-3">
                    <div id="modalVelocidades" style="
                        position: absolute;
                        top: -100px;
                        left: 150px;
                        transform: translateX(-70%);
                        width: 300px;
                        background-color: rgba(255, 255, 255, 0.9);
                        border: 1px solid #ccc;
                        border-radius: 8px;
                        padding: 15px;
                        box-shadow: 0 0 10px rgba(0,0,0,0.2);
                        z-index: 999;
                        text-align: center;
                    ">
                        <h5 style="color: #ff7f7f;">Velocidades Calculadas</h5>
                        <p><strong>Motor Izquierdo:</strong> <span id="velIzqTexto">0 </span> km/h</p>
                        <p><strong>Motor Derecho:</strong> <span id="velDerTexto">0 </span>km/h</p>
                        <p><strong>Posición X:</strong> <span id="posXTexto">0</span></p>
                        <p><strong>Posición Y:</strong> <span id="posYTexto">0</span></p>
                        <div style="margin-top: 10px;">
                            <button id="btnCambiarModo">Cambiar Modo</button>
                            <button id="btnEmpezar" style="display:none;">Empezar</button>
                        </div>
                            <span id="modoActual" style="margin-left:10px;">Modo: Punto a punto</span>
                            <span id="expl" style="display: none; font-size:10px;">(Ingrese punto a punto con click y precione empezar)</span>
                    </div>
                    <canvas id="simulationCanvas" width="600" height="400" style="border:1px solid #ccc;"></canvas>
                    <p style="margin-top:10px;">Usa las teclas de flechas (↑ ↓ ← →) para mover el vehículo.</p>
                    <span style="margin-top:10px; font-size:10px;">*Si los motores no tienen voltaje no andara el carro*</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container mt-3" style="max-width: 300px;">
        <h3 class="text-danger text-center mb-4">Modelos Cinemático y Dinámico</h3>
    
        <div class="card p-3 mb-3" style="font-size: 14px;">
            <h4 class="mb-2" style="font-size: 18px;">Cinemática</h4>
            <ul class="text-start mb-2">
                <li><b>Posición:</b> \( \dot{x} = v \cos(\theta) \), \( \dot{y} = v \sin(\theta) \)</li>
                <li><b>Orientación:</b> \( \dot{\theta} = \omega \)</li>
                <li><b>Velocidad lineal:</b> \( v = \frac{v_R + v_L}{2} \)</li>
                <li><b>Velocidad angular:</b> \( \omega = \frac{v_R - v_L}{L} \)</li>
            </ul>
        </div>
    
        <div class="card p-3 mb-3" style="font-size: 14px;">
            <h4 class="mb-2" style="font-size: 18px;">Dinámica</h4>
            <ul class="text-start mb-2">
                <li><b>Avance:</b> \( m \dot{v} = F_R + F_L \)</li>
                <li><b>Rotación:</b> \( I_z \dot{\omega} = (F_R - F_L) \times \frac{L}{2} \)</li>
            </ul>
        </div>
    
        <div class="card p-3 text-center" style="font-size: 14px;">
            <h4 class="mb-2" style="font-size: 18px;">Fuerzas (Cuerpo Libre)</h4>
            <div class="center">
                <img src="assets/img/CuerpoLibre.PNG" alt="Diagrama de Cuerpo Libre" class="img-fluid" style="max-width: 30%; height: auto;">
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    
    <script>
        const canvas = document.getElementById("simulationCanvas");
        const ctx = canvas.getContext("2d");
        
        let posX = 300;
        let posY = 200;
        let theta = 0;
        
        let vIzqMax = 0;
        let vDerMax = 0;
        
        let vIzq = 0;
        let vDer = 0;
        
        const L = 50;
        const keys = {};
        
        let puntos = [];
        let objetivo = null;
        let velocidadMovimiento = 2;
        
        let empezarMovimiento = false;
        let modoPuntoAPunto = true;
        let indiceTrayectoria = 0;
        let enMovimiento = false;
        
        const btnCambiarModo = document.getElementById('btnCambiarModo');
        const btnEmpezar = document.getElementById('btnEmpezar');
        const expl = document.getElementById('expl');
        const modoActualTexto = document.getElementById('modoActual');
    
        btnCambiarModo.addEventListener('click', () => {
            modoPuntoAPunto = !modoPuntoAPunto;
            puntos = [];
            objetivo = null;
            indiceTrayectoria = 0;
            enMovimiento = false;
            empezarMovimiento = false;
            posX = canvas.width/2;
            posY = canvas.height/2;
            theta = 0;
    
            if (modoPuntoAPunto) {
                expl.style.display = 'none';
                btnEmpezar.style.display = 'none';
                modoActualTexto.textContent = "Modo: Punto a punto";
            } else {
                btnEmpezar.style.display = 'inline-block';
                expl.style.display = 'inline-block';
                modoActualTexto.textContent = "Modo: Trayectoria";
            }
        });
    
        btnEmpezar.addEventListener('click', () => {
            if (!modoPuntoAPunto && puntos.length > 0) {
                empezarMovimiento = true;
                indiceTrayectoria = 0;
                enMovimiento = true;
            }
        });
    
        canvas.addEventListener('click', (event) => {
            const rect = canvas.getBoundingClientRect();
            const x = (event.clientX - rect.left) * (canvas.width / rect.width);
            const y = (event.clientY - rect.top) * (canvas.height / rect.height);
    
            if (modoPuntoAPunto) {
                puntos = [{x, y}];
                objetivo = {x, y};
                enMovimiento = true;
            } else {
                puntos.push({x, y});
            }
        });
    
        function moverEnTrayectoria() {
            if (!empezarMovimiento || !enMovimiento || puntos.length == 0 || indiceTrayectoria >= puntos.length) return;
    
            const objetivo = puntos[indiceTrayectoria];
            const dx = objetivo.x - posX;
            const dy = objetivo.y - posY;
            const distancia = Math.sqrt(dx*dx + dy*dy);
    
            const velocidad = 1;
            document.getElementById("velIzqTexto").textContent = velocidad * 3.6;
            document.getElementById("velDerTexto").textContent = velocidad * 3.6;
    
            if (distancia > 1) {
                const angulo = Math.atan2(dy, dx);
                posX += velocidad * Math.cos(angulo);
                posY += velocidad * Math.sin(angulo);
                theta = angulo;
            } else {
                indiceTrayectoria++;
                if (indiceTrayectoria >= puntos.length) {
                    enMovimiento = false;
                    empezarMovimiento = false;
                }
            }
        }
    
        function drawVehicle() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
    
            if (puntos.length > 1) {
                ctx.beginPath();
                ctx.moveTo(puntos[0].x, puntos[0].y);
                for (let i = 1; i < puntos.length; i++) {
                    ctx.lineTo(puntos[i].x, puntos[i].y);
                }
                ctx.strokeStyle = "red";
                ctx.lineWidth = 2;
                ctx.stroke();
            }
    
            for (const punto of puntos) {
                ctx.beginPath();
                ctx.arc(punto.x, punto.y, 5, 0, 2 * Math.PI);
                ctx.fillStyle = 'red';
                ctx.fill();
            }
    
            ctx.save();
            ctx.translate(posX, posY);
            ctx.rotate(theta);
            ctx.fillStyle = "blue";
            ctx.fillRect(-20, -10, 40, 20);
            ctx.restore();
        }
    
        function moverHaciaObjetivo() {
            if (objetivo) {
                const dx = objetivo.x - posX;
                const dy = objetivo.y - posY;
                const distancia = Math.sqrt(dx * dx + dy * dy);
    
                if (distancia > 1) {
                    const dirX = dx / distancia;
                    const dirY = dy / distancia;
    
                    posX += dirX * velocidadMovimiento;
                    posY += dirY * velocidadMovimiento;
                    theta = Math.atan2(dirY, dirX);
                } else {
                    objetivo = null;
                    enMovimiento = false;
                }
                document.getElementById("velIzqTexto").textContent = velocidadMovimiento * 3.6;
                document.getElementById("velDerTexto").textContent = velocidadMovimiento * 3.6;
            }
        }
    
        function updateFromKeyboard() {
            let avance = 0;
            let giro = 0;
    
            if (keys["ArrowUp"]) avance = 1;
            if (keys["ArrowDown"]) avance = -1;
            if (keys["ArrowLeft"]) giro = 1;
            if (keys["ArrowRight"]) giro = -1;
    
            vIzq = (avance * vIzqMax) + (giro * vIzqMax);
            vDer = (avance * vDerMax) - (giro * vDerMax);
        }
    
        function updatePosition() {
            const dt = 1;
    
            const v = (vIzq + vDer) / 2;
            const omega = (vDer - vIzq) / L;
    
            theta += omega * dt;
            posX += v * Math.cos(theta) * dt;
            posY += v * Math.sin(theta) * dt;
        }
    
        function loop() {
            updateFromKeyboard();
            updatePosition();
            drawVehicle();
    
            if (modoPuntoAPunto) {
                moverHaciaObjetivo();
            } else {
                moverEnTrayectoria();
            }
    
            document.getElementById("posXTexto").textContent = posX.toFixed(0);
            document.getElementById("posYTexto").textContent = posY.toFixed(0);
    
            requestAnimationFrame(loop);
        }
    
        function ajustarTamañoCanvas() {
            canvas.width = canvas.clientWidth;
            canvas.height = canvas.clientHeight;
        }
        ajustarTamañoCanvas();
    
        window.addEventListener("keydown", (e) => { keys[e.key] = true; });
        window.addEventListener("keyup", (e) => { keys[e.key] = false; });
    
        window.addEventListener("keydown", function (e) {
          const scrollKeys = ["ArrowUp", "ArrowDown", "ArrowLeft", "ArrowRight", " "];
          if (scrollKeys.includes(e.key)) {
            e.preventDefault();
          }
        }, { passive: false });
    
        document.getElementById("voltajeIzquierdo").addEventListener("change", simularMovimiento);
        document.getElementById("voltajeDerecho").addEventListener("change", simularMovimiento);
    
        function simularMovimiento() {
            const voltajeMax = 12;
            const velMax = 2;
    
            let voltajeIzq = parseFloat(document.getElementById("voltajeIzquierdo").value);
            let voltajeDer = parseFloat(document.getElementById("voltajeDerecho").value);
    
            voltajeIzq = Math.max(0, Math.min(voltajeIzq, voltajeMax));
            voltajeDer = Math.max(0, Math.min(voltajeDer, voltajeMax));
    
            vIzqMax = (voltajeIzq / voltajeMax) * velMax;
            vDerMax = (voltajeDer / voltajeMax) * velMax;
    
            document.getElementById("velIzqTexto").textContent = (vIzqMax * 3.6).toFixed(2);
            document.getElementById("velDerTexto").textContent = (vDerMax * 3.6).toFixed(2);
        }
    
        drawVehicle();
        loop();
    </script>
</body>
</html>
