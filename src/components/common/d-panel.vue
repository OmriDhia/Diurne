<template>
    <div class="col-xl-12 col-lg-12 col-sm-12 h100">
        <div class="panel br-6 p-2 h100">
            <slot name="panel-header"></slot>
            <div class="row layout-top-spacing align-items-center mb-2 ms-1">
                <div class="col-12">
                    <slot name="panel-body"></slot>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .h100{
        height: 100%;
    }
</style>
