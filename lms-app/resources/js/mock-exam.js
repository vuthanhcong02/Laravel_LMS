// Mock Exam Student View Script

// Mobile sidebar toggle
window.toggleNavSidebar = function(forceState) {
    const sidebar = document.getElementById('nav-sidebar');
    const overlay = document.getElementById('nav-overlay');
    const isMobile = window.innerWidth < 768;
    if (!isMobile) return;

    const isOpen = sidebar.classList.contains('flex');
    const shouldOpen = forceState !== undefined ? forceState : !isOpen;

    if (shouldOpen) {
        sidebar.classList.remove('hidden');
        sidebar.classList.add('flex');
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    } else {
        sidebar.classList.add('hidden');
        sidebar.classList.remove('flex');
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

window.scrollToQuestion = function(qNum) {
    const el = document.getElementById('q-' + qNum);
    if (el) {
        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        el.style.transition = 'box-shadow 0.3s ease';
        el.style.boxShadow = '0 0 0 3px rgba(232, 146, 122, 0.5)';
        setTimeout(() => { el.style.boxShadow = ''; }, 1200);
    }
    if (window.innerWidth < 768) window.toggleNavSidebar(false);
}

window.confirmSubmit = function() {
    const answered = document.querySelectorAll('input[type="radio"]:checked').length;
    document.getElementById('confirm-answered').textContent = answered;
    const modal = document.getElementById('confirm-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

window.closeModal = function() {
    const modal = document.getElementById('confirm-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.addEventListener('DOMContentLoaded', () => {
    const confirmModal = document.getElementById('confirm-modal');
    if (confirmModal) {
        confirmModal.addEventListener('click', function(e) {
            if (e.target === this) window.closeModal();
        });
    }
});

// Audio playback logic
let currentAudio = null;
let currentBtn = null;

window.playAudio = function(url, btnElement) {
    if (currentAudio && currentAudio.src.endsWith(url) && !currentAudio.paused) {
        currentAudio.pause();
        resetButton(btnElement);
        return;
    }

    if (currentAudio) {
        currentAudio.pause();
        if (currentBtn) resetButton(currentBtn);
    }

    currentAudio = new Audio(url);
    currentBtn = btnElement;

    // Change icon to pause
    const icon = btnElement.querySelector('.material-symbols-outlined');
    if (icon) icon.textContent = 'pause_circle';
    btnElement.classList.add('playing-audio');

    currentAudio.play();

    currentAudio.onended = () => {
        resetButton(btnElement);
    };
}

function resetButton(btnElement) {
    const icon = btnElement.querySelector('.material-symbols-outlined');
    if (icon) {
        icon.textContent = icon.textContent === 'pause_circle' ? 'play_circle' : 'volume_up';
    }
    btnElement.classList.remove('playing-audio');
}

// Sidebar update logic
window.updateSidebar = function(qNum) {
    const btn = document.getElementById('nav-btn-' + qNum);
    if (btn) {
        btn.classList.remove('bg-slate-50', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-400', 'border-slate-200', 'dark:border-slate-700');
        btn.classList.add('bg-primary', 'text-white', 'border-transparent', 'shadow-sm');
    }
    
    // Update progress bar
    const total = document.querySelectorAll('.nav-btn-item').length;
    const answered = document.querySelectorAll('.nav-btn-item.bg-primary').length;
    const bar = document.getElementById('progress-bar');
    if (bar) {
        bar.style.width = `${(answered / total) * 100}%`;
    }
}

// Timer logic
let timeRemaining = 0;
let timerInterval = null;

window.initTimer = function(durationInMinutes) {
    timeRemaining = durationInMinutes * 60;
    const display = document.getElementById('exam-timer-display');
    
    if (!display) return;
    
    timerInterval = setInterval(() => {
        if (timeRemaining > 0) {
            timeRemaining--;
            const m = Math.floor(timeRemaining / 60).toString().padStart(2, '0');
            const s = (timeRemaining % 60).toString().padStart(2, '0');
            display.textContent = `${m}:${s}`;
        } else {
            clearInterval(timerInterval);
            document.getElementById('exam-form').submit();
        }
    }, 1000);
}
