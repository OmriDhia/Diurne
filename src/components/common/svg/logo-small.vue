<template>
    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="34"
        viewBox="0 0 391 109">
        <defs>
            <filter id="a" x="0" y="0" height="34" filterUnits="userSpaceOnUse">
                <feOffset dy="3" input="SourceAlpha" />
                <feGaussianBlur stdDeviation="3" result="b" />
                <feFlood flood-opacity="0.161" />
                <feComposite operator="in" in2="b" />
                <feComposite in="SourceGraphic" />
            </filter>
        </defs>
        <g class="b" transform="matrix(1, 0, 0, 1, 0, 0)"><text class="a" transform="translate(9 80)">
                <tspan x="0" y="0">DIURNE</tspan>
            </text></g>
    </svg>
</template>
<style>
.a {
    fill: #fff;
    font-size: 83px;
    font-family: 'OptimusPrincepsSemiBold';
    letter-spacing: 0.1em;
}

.b {
    filter: url(#a);
}
</style>
