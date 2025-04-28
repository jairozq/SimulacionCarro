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
            width: 90%; /* Más ancho para el área general */
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
            width: 100px; /* Inputs más angostos */
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
                
                <button onclick="simularMovimiento()" style="background-color:#ff7f7f;margin-top:15px;">Aplicar</button>
            </div>
            <div class="container mt-5">
                <h3 style="color: #ff7f7f;">Simulación Gráfica del Vehículo</h3>
            
                <div class="card p-4 my-3">
                    <div id="modalVelocidades" style="
                        position: absolute;
                        top: 100px;
                        left: 50%;
                        transform: translateX(-50%);
                        width: 300px;
                        background-color: rgba(255, 255, 255, 0.9);
                        border: 1px solid #ccc;
                        border-radius: 8px;
                        padding: 15px;
                        box-shadow: 0 0 10px rgba(0,0,0,0.2);
                        z-index: 999;
                        display: none;
                        text-align: center;
                    ">
                        <h5 style="color: #ff7f7f;">Velocidades Calculadas</h5>
                        <p><strong>Motor Izquierdo:</strong> <span id="velIzqTexto">0 </span> km/h</p>
                        <p><strong>Motor Derecho:</strong> <span id="velDerTexto">0 </span>km/h</p>
                        <button onclick="document.getElementById('modalVelocidades').style.display='none'">Cerrar</button>
                    </div>
                    <canvas id="simulationCanvas" width="600" height="400" style="border:1px solid #ccc;"></canvas>
                    <p style="margin-top:10px;">Usa las teclas de flechas (↑ ↓ ← →) para mover el vehículo.</p>
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
        let theta = 0; // orientación (radianes)
        
        let vIzqMax = 0; // velocidad máxima motor izquierdo
        let vDerMax = 0; // velocidad máxima motor derecho
        
        let vIzq = 0;    // velocidad actual izquierda
        let vDer = 0;    // velocidad actual derecha
        
        const L = 50; // distancia entre ruedas
        const keys = {};
        
        function drawVehicle() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.save();
            ctx.translate(posX, posY);
            ctx.rotate(theta);
            ctx.fillStyle = "blue";
            ctx.fillRect(-20, -10, 40, 20);
            ctx.restore();
        }
        
        function simularMovimiento() {
            const voltajeMax = 12;
            const velMax = 2;
        
            let voltajeIzq = parseFloat(document.getElementById("voltajeIzquierdo").value);
            let voltajeDer = parseFloat(document.getElementById("voltajeDerecho").value);
        
            voltajeIzq = Math.max(0, Math.min(voltajeIzq, voltajeMax));
            voltajeDer = Math.max(0, Math.min(voltajeDer, voltajeMax));
        
            vIzqMax = (voltajeIzq / voltajeMax) * velMax;
            vDerMax = (voltajeDer / voltajeMax) * velMax;
            
            document.getElementById("velIzqTexto").textContent = vIzqMax.toFixed(2)* 3.6;
            document.getElementById("velDerTexto").textContent = vDerMax.toFixed(2)* 3.6;
            document.getElementById("modalVelocidades").style.display = "block";
        }
        
        // Moverse con teclas
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
        
        // Actualizar posición según vIzq y vDer
        function updatePosition() {
            const dt = 1;
        
            const v = (vIzq + vDer) / 2;
            const omega = (vDer - vIzq) / L;
        
            theta += omega * dt;
            posX += v * Math.cos(theta) * dt;
            posY += v * Math.sin(theta) * dt;
        }
        
        window.addEventListener("keydown", (e) => { keys[e.key] = true; });
        window.addEventListener("keyup", (e) => { keys[e.key] = false; });
        
        function loop() {
            updateFromKeyboard();
            updatePosition();
            drawVehicle();
            requestAnimationFrame(loop);
        }
        
        drawVehicle();
        loop();
        
        
        window.addEventListener("keydown", function (e) {
          const scrollKeys = ["ArrowUp", "ArrowDown", "ArrowLeft", "ArrowRight"," "];
    
          if (scrollKeys.includes(e.key)) {
            e.preventDefault();
          }
        }, { passive: false });
    </script>
</body>
</html>
