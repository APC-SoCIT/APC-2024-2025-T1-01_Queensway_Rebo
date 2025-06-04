<style>
   .loading-overlay {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.7); /* Black with 70% opacity */
    z-index: 9999;
    display: none; /* hidden by default */
    justify-content: center;
    align-items: center;
}

/* Show overlay when active */
.loading-overlay.active {
    display: flex !important;
}

/* Pulsing dots spinner container */
.spinner {
    display: flex;
    gap: 10px;
}

/* Each dot */
.spinner div {
    width: 14px;
    height: 14px;
    background-color: white;
    border-radius: 50%;
    animation: pulse 1.2s infinite ease-in-out;
}

/* Animate dots with staggered delays */
.spinner div:nth-child(1) {
    animation-delay: 0s;
}
.spinner div:nth-child(2) {
    animation-delay: 0.2s;
}
.spinner div:nth-child(3) {
    animation-delay: 0.4s;
}

/* Pulse animation */
@keyframes pulse {
    0%, 80%, 100% {
        transform: scale(0.8);
        opacity: 0.6;
    }
    40% {
        transform: scale(1.2);
        opacity: 1;
    }
}

</style>

<div id="{{ $id ?? 'loading-overlay' }}" class="loading-overlay">
  <div class="spinner">
    <div></div>
    <div></div>
    <div></div>
  </div>
</div>
