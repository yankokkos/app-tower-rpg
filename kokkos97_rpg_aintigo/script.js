function previewImage() {
    const imageUrl = document.getElementById('aparencia_personalidade').value; // Certifique-se de que o ID está correto
    const imgPreview = document.getElementById('imgPreview');
    imgPreview.src = imageUrl;
    imgPreview.style.display = imageUrl ? 'block' : 'none'; // Exibe a imagem se o URL não estiver vazio
}

let totalPoints = 30; // Total de pontos disponíveis
const attributes = {
    for: 0,
    des: 0,
    con: 0,
    int: 0,
    sab: 0,
    car: 0,
    poder: 0
};

function updatePoints() {
    const pointsDisplay = document.getElementById('pointsDisplay');
    pointsDisplay.innerText = `Pontos disponíveis: ${totalPoints}`;
}

function getCost(value) {
    if (value <= 3) return 1;
    if (value <= 6) return 2;
    if (value <= 9) return 3;
    return 0; // Para valores acima de 9, não deve haver custo
}

function adjustAttribute(attribute, adjustment) {
    const currentValue = attributes[attribute];
    let newValue = currentValue + adjustment;

    // Verifica se o novo valor excede 9
    if (newValue > 9) {
        alert("O valor máximo para atributos é 9.");
        return; // Impede a alteração se o novo valor for maior que 9
    }

    // Se o ajuste for negativo (decremento)
    if (adjustment < 0) {
        if (newValue < 0) {
            alert("O valor mínimo para atributos é 0.");
            return; // Impede a alteração se o novo valor for menor que 0
        }
        const cost = getCost(currentValue); // Custo baseado no valor anterior
        attributes[attribute] = newValue;
        totalPoints += cost; // Adiciona o custo de volta aos pontos
    } else {
        // Se o ajuste for positivo (incremento)
        const cost = getCost(newValue); // Custo baseado no novo valor
        if (totalPoints >= cost) {
            attributes[attribute] = newValue;
            totalPoints -= cost; // Subtrai o custo dos pontos
        } else {
            alert("Pontos insuficientes para aumentar o atributo.");
            return; // Impede a alteração se não houver pontos suficientes
        }
    }

    // Atualiza o valor exibido e os pontos disponíveis
    document.getElementById(attribute).value = newValue;
    document.getElementById(attribute + 'Hidden').value = newValue; // Atualiza o campo oculto
    updatePoints(); // Atualiza a exibição dos pontos disponíveis
}

function updateAttributeValue(attribute, inputId) {
    const value = parseInt(document.getElementById(inputId).value) || 0;
    document.getElementById(attribute + 'Hidden').value = value; // Atualiza o campo oculto
    calculateTotals(); // Atualiza os totais
}

function calculateTotals() {
    const forca = parseInt(document.getElementById('for').value) || 0;
    const con = parseInt(document.getElementById('con').value) || 0;
    const pvsAdicional = parseInt(document.getElementById('pvs').value) || 0;
    const sab = parseInt(document.getElementById('sab').value) || 0;
    const pssAdicional = parseInt(document.getElementById('pss').value) || 0;
    const int = parseInt(document.getElementById('int').value) || 0;
    const poder = parseInt(document.getElementById('poder').value) || 0;
    const pesAdicional = parseInt(document.getElementById('pes').value) || 0;

    const pvTotal = (forca + (con * 2) + pvsAdicional)*3;
    const psTotal = (sab + (con * 2) + pssAdicional)*3;
    const peTotal = int + Math.floor(poder * Math.PI) + pesAdicional;

    document.getElementById('pvTotal').innerText = `PVs Total: ${pvTotal}`;
    document.getElementById('psTotal').innerText = `PSs Total: ${psTotal}`;
    document.getElementById('peTotal').innerText = `PEs Total: ${peTotal}`;
}

// Atualiza a exibição inicial dos pontos
updatePoints();

function addInput(containerId, name, placeholder) {
    const container = document.getElementById(containerId);
    const newInput = document.createElement('div');
    newInput.className = `${name}-input`;
    newInput.innerHTML = `
        <input type="text" name="${name}[]" placeholder="${placeholder}" required>
        <button type="button" onclick="removeElement(this)">Remover</button>
    `;
    container.appendChild(newInput);
}

function addDream() {
    addInput('dreamsContainer', 'sonhos', 'Digite um sonho');
}

function addFear() {
    addInput('fearsContainer', 'medos', 'Digite um medo');
}

function removeElement(button) {
    const inputContainer = button.parentElement;
    inputContainer.remove();
}