const modal = new bootstrap.Modal(document.getElementById('confirmPasswordModal'));
const modalPasswordInput = document.getElementById('modalPasswordInput');
const modalErrorMsg = document.getElementById('modalErrorMsg');
const pageMessage = document.getElementById('pageMessage');

let activeField = null;
let verifiedPassword = null;

async function callServer(action, field, value = '') {
    const res = await fetch('api/profile_ajax.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action, field, value, current_password: verifiedPassword })
    });
    return res.json();
}

function showMessage(text, isSuccess) {
    pageMessage.textContent = text;
    pageMessage.style.color = isSuccess ? '#2DD4BF' : '#ff6b6b';
    setTimeout(() => pageMessage.textContent = '', 3000);
}

document.querySelectorAll('.edit-icon-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        activeField = btn.dataset.field;
        modalPasswordInput.value = '';
        modalErrorMsg.textContent = '';
        modal.show();
    });
});

document.getElementById('modalConfirmBtn').addEventListener('click', confirmPassword);
modalPasswordInput.addEventListener('keypress', e => {
    if (e.key === 'Enter') confirmPassword();
});

async function confirmPassword() {
    verifiedPassword = modalPasswordInput.value;

    const data = await callServer('verify', activeField);

    if (!data.success) {
        modalErrorMsg.textContent = 'Incorrect password';
        return;
    }

    modal.hide();
    unlockField(activeField, data.value);
}

function unlockField(field, value) {
    const input = document.getElementById('field-' + field);
    const box = input.closest('.field-with-icon');

    input.readOnly = false;
    input.classList.add('editing');
    input.value = (field === 'password') ? '' : value;
    if (field === 'password') input.placeholder = 'Enter new password';
    input.focus();

    box.querySelector('.edit-icon-btn').style.display = 'none';
    box.querySelector('.save-icon-btn').style.display = 'inline-block';
}


document.querySelectorAll('.save-icon-btn').forEach(btn => {
    btn.addEventListener('click', () => saveField(btn.dataset.field));
});

async function saveField(field) {
    const input = document.getElementById('field-' + field);
    const box = input.closest('.field-with-icon');

    const data = await callServer('update', field, input.value);

    if (!data.success) {
        showMessage(data.message || 'Something went wrong', false);
        return;
    }

    input.readOnly = true;
    input.classList.remove('editing');
    input.value = (field === 'password') ? '**********' : data.value;

    box.querySelector('.edit-icon-btn').style.display = 'inline-block';
    box.querySelector('.save-icon-btn').style.display = 'none';

    verifiedPassword = null;
    showMessage('Updated successfully', true);
}