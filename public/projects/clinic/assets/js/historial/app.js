import { toothTypes, teethPositions } from './toothData.js';

document.addEventListener('DOMContentLoaded', function () {
    // Elementos del DOM
    const dentalChart = document.getElementById('dental-chart-container');
    const toothNumberInput = document.getElementById('tooth-number');
    const toothTypeInput = document.getElementById('tooth-type');
    const toothStatusSelect = document.getElementById('tooth-status');
    const toothTreatmentSelect = document.getElementById('tooth-treatment');
    const toothNotesTextarea = document.getElementById('tooth-notes');
    const saveToothBtn = document.getElementById('save-tooth');
    const clearToothBtn = document.getElementById('clear-tooth');
    const saveAllBtn = document.getElementById('save-all');
    const modal = document.getElementById('modal');
    const modalClose = document.getElementById('modal-close');
    const modalCancel = document.getElementById('modal-cancel');
    const modalConfirm = document.getElementById('modal-confirm');
    const modalTitle = document.getElementById('modal-title');
    const modalMessage = document.getElementById('modal-message');

    // Estado de la aplicación
    let selectedTooth = null;
    const dentalRecords = {};

    // Generar áreas de dientes dinámicamente
    teethPositions.forEach(tooth => {
        const toothArea = document.createElement('div');
        toothArea.className = 'tooth-area';
        toothArea.style.cssText = `
            width: ${tooth.width}px;
            height: ${tooth.height}px;
            top: ${tooth.y}px;
            left: ${tooth.x}px;
        `;
        toothArea.dataset.tooth = tooth.number;

        const toothNumber = document.createElement('span');
        toothNumber.className = 'tooth-number';
        toothNumber.textContent = tooth.number;

        toothArea.appendChild(toothNumber);
        dentalChart.appendChild(toothArea);

        // Evento click para cada diente
        toothArea.addEventListener('click', function () {
            selectTooth(tooth.number);
        });
    });

    // Función para seleccionar un diente
    function selectTooth(toothNumber) {
        selectedTooth = toothNumber;

        // Remover selección previa
        document.querySelectorAll('.tooth-area').forEach(area => {
            area.classList.remove('selected');
        });

        // Resaltar diente seleccionado
        document.querySelector(`.tooth-area[data-tooth="${toothNumber}"]`).classList.add('selected');

        // Actualizar formulario
        const toothInfo = toothTypes[toothNumber] || { name: "Desconocido", type: "unknown" };
        toothNumberInput.value = toothNumber;
        toothTypeInput.value = toothInfo.name;

        // Cargar datos existentes si los hay
        if (dentalRecords[toothNumber]) {
            toothStatusSelect.value = dentalRecords[toothNumber].status || 'good';
            toothTreatmentSelect.value = dentalRecords[toothNumber].treatment || 'none';
            toothNotesTextarea.value = dentalRecords[toothNumber].notes || '';
        } else {
            toothStatusSelect.value = 'good';
            toothTreatmentSelect.value = 'none';
            toothNotesTextarea.value = '';
        }
    }

    // Evento para guardar datos de un diente
    saveToothBtn.addEventListener('click', function () {
        if (!selectedTooth) {
            showModal('Error', 'Por favor selecciona un diente primero');
            return;
        }

        dentalRecords[selectedTooth] = {
            status: toothStatusSelect.value,
            treatment: toothTreatmentSelect.value,
            notes: toothNotesTextarea.value,
            date: new Date().toISOString()
        };

        showModal('Éxito', `Diente ${selectedTooth} guardado correctamente`);
    });

    // Evento para limpiar formulario
    clearToothBtn.addEventListener('click', function () {
        toothNumberInput.value = '';
        toothTypeInput.value = '';
        toothStatusSelect.value = 'good';
        toothTreatmentSelect.value = 'none';
        toothNotesTextarea.value = '';
        selectedTooth = null;

        document.querySelectorAll('.tooth-area').forEach(area => {
            area.classList.remove('selected');
        });
    });

    // Evento para guardar todos los datos
    saveAllBtn.addEventListener('click', function () {
        showModal('Confirmación', '¿Estás seguro de guardar todo el historial dental?', true, () => {
            // Aquí iría la lógica para enviar todos los datos al servidor
            console.log('Datos a enviar:', dentalRecords);
            showModal('Éxito', 'Historial dental guardado correctamente');
        });
    });

    // Funciones para el modal
    function showModal(title, message, showCancel = false, confirmCallback = null) {
        modalTitle.textContent = title;
        modalMessage.textContent = message;

        modalCancel.style.display = showCancel ? 'block' : 'none';
        modal.classList.remove('hidden');
        modal.classList.add('modal-enter');

        // Configurar callback de confirmación
        const handleConfirm = () => {
            if (confirmCallback) confirmCallback();
            hideModal();
        };

        modalConfirm.onclick = handleConfirm;
    }

    function hideModal() {
        modal.classList.add('hidden');
        modal.classList.remove('modal-enter');
    }

    // Eventos del modal
    modalClose.addEventListener('click', hideModal);
    modalCancel.addEventListener('click', hideModal);

    // Cerrar modal al hacer clic fuera del contenido
    modal.addEventListener('click', function (e) {
        if (e.target === modal) hideModal();
    });

    // Con esto (siempre activo):
    document.getElementById('dental-image').addEventListener('click', function (e) {
        console.log(`Posición: X:${e.offsetX}, Y:${e.offsetY}`);
    });
});