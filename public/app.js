document.addEventListener('click', function (e) {
    if (e.target && (e.target.classList.contains('btn-share-whatsapp') || e.target.closest('.btn-share-whatsapp'))) {
        const button = e.target.classList.contains('btn-share-whatsapp') ? e.target : e.target.closest('.btn-share-whatsapp');
        
        const elementId = button.getAttribute('data-element-id'); // El resultado final
        const mensajeBase = button.getAttribute('data-mensaje');
        const extra1 = button.getAttribute('data-extra1'); // ID del Precio Original
        const extra2 = button.getAttribute('data-extra2'); // ID del % Descuento
        const type = button.getAttribute('data-type');

        const resFinal = document.getElementById(elementId)?.innerText;
        let textoCompleto = `*${mensajeBase}*\n\n`;

        if (type === 'descuento' && extra1 && extra2) {
            const vOriginal = document.getElementById(extra1).value;
            const vDesc = document.getElementById(extra2).value;
            
            textoCompleto += `💰 *Precio Original:* $${parseInt(vOriginal).toLocaleString('es-CO')}\n`;
            textoCompleto += `📉 *Descuento:* ${vDesc}%\n`;
            textoCompleto += `✅ *Precio Final:* ${resFinal}\n\n`;
        } else if (type === 'equilibrio' && extra1 && extra2) {
            const vFijos = document.getElementById(extra1).value;
            const vPrecio = document.getElementById(extra2).value;
            
            textoCompleto += `📉 *Costos Fijos:* $${parseInt(vFijos).toLocaleString('es-CO')}\n`;
            textoCompleto += `🏷️ *Precio de Venta:* $${parseInt(vPrecio).toLocaleString('es-CO')}\n`;
            textoCompleto += `🎯 *Meta de equilibrio:* ${resFinal}\n\n`;
        } else if (type === 'interes' && extra1 && extra2) {
            const vCapital = document.getElementById(extra1).value;
            const vTasa = document.getElementById(extra2).value;
            const labelTipo = document.getElementById('tipo-interes-label').innerText;
            
            textoCompleto += `🏦 *Tipo:* ${labelTipo}\n`;
            textoCompleto += `💰 *Inversión Inicial:* $${parseInt(vCapital).toLocaleString('es-CO')}\n`;
            textoCompleto += `📈 *Tasa Anual:* ${vTasa}%\n`;
            textoCompleto += `🏁 *Proyección Final:* ${resFinal}\n\n`;
        } else if (type === 'comision' && extra1 && extra2) {
            const vVenta = document.getElementById(extra1).value;
            const vPorcentaje = document.getElementById(extra2).value;
            
            textoCompleto += `💰 *Venta Realizada:* $${parseInt(vVenta).toLocaleString('es-CO')}\n`;
            textoCompleto += `📉 *Porcentaje:* ${vPorcentaje}%\n`;
            textoCompleto += `💵 *Comisión Ganada:* ${resFinal}\n\n`;
        }else if (type === 'rentabilidad' && extra1 && extra2) {
            const vInversion = document.getElementById(extra1).value;
            const vFinal = document.getElementById(extra2).value;
            const ganancia = parseInt(vFinal) - parseInt(vInversion);
            
            textoCompleto += `💰 *Inversión Inicial:* $${parseInt(vInversion).toLocaleString('es-CO')}\n`;
            textoCompleto += `📈 *Valor Obtenido:* $${parseInt(vFinal).toLocaleString('es-CO')}\n`;
            textoCompleto += `💵 *Ganancia Neta:* $${ganancia.toLocaleString('es-CO')}\n`;
            textoCompleto += `📊 *Rentabilidad (ROI):* ${resFinal}\n\n`;
        }else if (type === 'laboral' && extra1 && extra2) {
            const vSalario = document.getElementById(extra1).value;
            const dias = document.getElementById('res-total-liq').innerText; // Reutilizamos el total para el mensaje
            
            textoCompleto += `📄 *Liquidación Estimada*\n`;
            textoCompleto += `💰 *Salario Base:* $${vSalario}\n`;
            textoCompleto += `📅 *Total a Recibir:* ${resFinal}\n\n`;
            textoCompleto += `_Nota: Este es un valor informativo aproximado._\n`;
        }else if (type === 'extras' && extra1) {
            const vSalario = document.getElementById(extra1).value;
            
            textoCompleto += `⏰ *Cálculo de Horas Extras*\n`;
            textoCompleto += `💰 *Salario Base:* $${vSalario}\n`;
            textoCompleto += `💵 *Total Recargos:* ${resFinal}\n\n`;
            textoCompleto += `_Recuerda que este valor es antes de descuentos de ley._\n`;
        }else if (type === 'cesantias' && extra1) {
            const vSalario = document.getElementById(extra1).value;
            
            textoCompleto += `🏦 *Cálculo de Cesantías e Intereses*\n`;
            textoCompleto += `💰 *Salario Base:* $${vSalario}\n`;
            textoCompleto += `📊 *Total a Recibir:* ${resFinal}\n\n`;
            textoCompleto += `_Generado en Tu Ayuda IO_\n`;
        }else if (type === 'prima' && extra1) {
            const vSalario = document.getElementById(extra1).value;
            
            textoCompleto += `🎁 *Cálculo de Prima de Servicios*\n`;
            textoCompleto += `💰 *Salario Base:* $${vSalario}\n`;
            textoCompleto += `✅ *Valor de Prima:* ${resFinal}\n\n`;
            textoCompleto += `_Calculado en Tu Ayuda IO_\n`;
        }else if (type === 'indemnizacion' && extra1) {
            const vSalario = document.getElementById(extra1).value;
            const vTipo = document.getElementById('tipo_contrato').value;
            
            textoCompleto += `⚖️ *Cálculo de Indemnización*\n`;
            textoCompleto += `📄 *Contrato:* ${vTipo.toUpperCase()}\n`;
            textoCompleto += `💰 *Salario Base:* $${vSalario}\n`;
            textoCompleto += `✅ *Total Indemnización:* ${resFinal}\n\n`;
            textoCompleto += `_Nota: Este cálculo es para despidos sin justa causa._\n`;
        }else if (type === 'independiente' && extra1) {
            const vHonorarios = document.getElementById(extra1).value;
            
            textoCompleto += `👤 *Seguridad Social Independiente*\n`;
            textoCompleto += `💰 *Honorarios:* $${vHonorarios}\n`;
            textoCompleto += `💵 *Dinero Libre:* ${resFinal}\n\n`;
            textoCompleto += `_Calculado con IBC al 40% en Tu Ayuda IO_\n`;
        }else if (type === 'credito' && extra1) {
            const vMonto = document.getElementById(extra1).value;            
            textoCompleto += `💰 *Simulación de Crédito*\n`;
            textoCompleto += `💵 *Monto solicitado:* $${vMonto}\n`;
            textoCompleto += `📊 *Total final a pagar:* ${resFinal}\n\n`;
            textoCompleto += `_Calculado en Tu Ayuda IO_\n`;
        }else if (type === 'cuota' && extra1) {
            const vMonto = document.getElementById(extra1).value;
            
            textoCompleto += `📅 *Mensualidad de Préstamo*\n`;
            textoCompleto += `💰 *Préstamo de:* $${vMonto}\n`;
            textoCompleto += `✅ *Cuota Mensual:* ${resFinal}\n\n`;
            textoCompleto += `_Calculado con seguro incluido en Tu Ayuda IO_\n`;
        }else if (type === 'hipoteca' && extra1) {
            const vVivienda = document.getElementById(extra1).value;
            
            textoCompleto += `🏠 *Simulación de Hipoteca*\n`;
            textoCompleto += `🏗️ *Valor Vivienda:* $${vVivienda}\n`;
            textoCompleto += `✅ *Cuota Mensual:* ${resFinal}\n\n`;
            textoCompleto += `_Calculado para crédito en pesos en Tu Ayuda IO_\n`;
        }else if (type === 'hipoteca' || type === 'arriendo_compra') {
            // Reutilizamos o creamos un caso para esta comparación
            textoCompleto += `🏠 *Comparativa Vivienda*\n`;
            textoCompleto += `📍 *Resultado:* ${document.getElementById('res-veredicto').innerText}\n`;
            textoCompleto += `📊 *Gasto Compra (10 años):* ${resFinal}\n\n`;
            textoCompleto += `_Decisión inteligente con Tu Ayuda IO_\n`;
        }else if (type === 'gasolina' && extra1) {
            const vDistancia = document.getElementById(extra1).value;
            
            textoCompleto += `⛽ *Consumo de Gasolina*\n`;
            textoCompleto += `🛣️ *Distancia:* ${vDistancia} km\n`;
            textoCompleto += `💰 *Costo Total:* ${resFinal}\n\n`;
            textoCompleto += `_¡Viaja seguro con Tu Ayuda IO!_\n`;
        } else if (type === 'promedio_ponderado') {
            textoCompleto += `🎓 *Mi Promedio Académico*\n`;
            textoCompleto += `📊 *Resultado:* ${resFinal}\n\n`;
            textoCompleto += `_Calculado en Tu Ayuda IO_\n`;

        } else if (type === 'conversion_notas') {
            const vNota = document.getElementById('nota_actual').value;
            textoCompleto += `🎓 *Equivalencia de Notas*\n`;
            textoCompleto += `📝 *Nota Original:* ${vNota}\n`;
            textoCompleto += `✅ *Nota Convertida:* ${resFinal}\n\n`;
            textoCompleto += `_Calculado en Tu Ayuda IO_\n`;
        } else if (type === 'regla_tres') {
            const vA = document.getElementById('valor_a').value;
            const vB = document.getElementById('valor_b').value;
            const vC = document.getElementById('valor_c').value;            
            textoCompleto += `📐 *Regla de Tres Simple*\n`;
            textoCompleto += `🔹 Si ${vA} ➔ ${vB}\n`;
            textoCompleto += `🔸 Entonces ${vC} ➔ *${resFinal}*\n\n`;
            textoCompleto += `_Calculado en Tu Ayuda IO_\n`;
        } else if (type === 'estadistica') {
            textoCompleto += `📊 *Análisis Estadístico*\n`;
            textoCompleto += `✅ *Resultados:* ${resFinal}\n\n`;
            textoCompleto += `_Calculado en Tu Ayuda IO_\n`;
        } else if (type === 'developer') {
            const vDestino = document.getElementById('formato_destino').value.toUpperCase();
            
            textoCompleto += `💻 *Herramienta para Desarrolladores*\n`;
            textoCompleto += `🔄 *Conversión realizada:* a ${vDestino}\n`;
            textoCompleto += `🚀 *Estado:* ¡Éxito!\n\n`;
            textoCompleto += `_Optimiza tu código en Tu Ayuda IO_\n`;
        } else if (type === 'validador-json') {
            textoCompleto += `💻 *Validador de Código*\n`;
            textoCompleto += `🔍 *Resultado:* ${resFinal}\n\n`;
            textoCompleto += `_Validado en Tu Ayuda IO_\n`;
        } else if (type === 'guid') {
            const cantidad = document.getElementById('cantidad_guid').value;
            
            textoCompleto += `🆔 *Generador de UUID / GUID*\n`;
            textoCompleto += `✅ Se han generado ${cantidad} identificadores únicos.\n`;
            textoCompleto += `🚀 *Estado:* Listos para usar en base de datos.\n\n`;
            textoCompleto += `_Generado en Tu Ayuda IO_\n`;
        } else if (type === 'hash') {
            const algoritmo = document.getElementById('hash_algo').value;
            
            textoCompleto += `🔐 *Generador de Hash*\n`;
            textoCompleto += `⚙️ *Algoritmo:* ${algoritmo}\n`;
            textoCompleto += `✅ *Hash:* ${resFinal}\n\n`;
            textoCompleto += `_Seguridad digital con Tu Ayuda IO_\n`;
        } else if (type === 'regex') {
            const vPattern = document.getElementById('regex_pattern').value;
            
            textoCompleto += `🔍 *Probador Regex*\n`;
            textoCompleto += `⚙️ *Patrón:* /${vPattern}/g\n`;
            textoCompleto += `📊 *Resultado:* ${resFinal}\n\n`;
            textoCompleto += `_Depura tus patrones en Tu Ayuda IO_\n`;
       } else if (type === 'base64') {
            const vOriginal = document.getElementById('base64_text').value;
            const vConvertido = document.getElementById('base64_code').value;
            
            // Limitamos el texto en el mensaje por si es muy largo
            const originalShort = vOriginal.length > 50 ? vOriginal.substring(0, 50) + "..." : vOriginal;
            const convertidoShort = vConvertido.length > 100 ? vConvertido.substring(0, 100) + "..." : vConvertido;

            textoCompleto += `🔗 *Conversor Base64*\n\n`;
            textoCompleto += `📄 *Texto Original:*\n${originalShort}\n\n`;
            textoCompleto += `🔐 *Resultado Base64:*\n${convertidoShort}\n\n`;
            textoCompleto += `_Codifica tus datos en Tu Ayuda IO_`;
        }
        else {
            textoCompleto += `📊 *Resultado:* ${resFinal}\n\n`;
        }

        textoCompleto += `Calculado en: ${window.location.href}`;
        
        const urlWhatsapp = `https://api.whatsapp.com/send?text=${encodeURIComponent(textoCompleto)}`;
        window.open(urlWhatsapp, '_blank');
    }
});

// Función para dar formato de miles a los inputs
function aplicarMascaraPesos(input) {
    input.addEventListener('input', function(e) {
        // Quitamos cualquier caracter que no sea número
        let value = e.target.value.replace(/\D/g, "");
        
        // Formateamos con puntos de miles
        if (value !== "") {
            e.target.value = new Intl.NumberFormat('es-CO').format(value);
        }
    });
}

// Función auxiliar para obtener el número real (sin puntos) antes de calcular
function obtenerValorLimpio(id) {
    const rawValue = document.getElementById(id).value;
    return parseFloat(rawValue.replace(/\./g, '')) || 0;
}
