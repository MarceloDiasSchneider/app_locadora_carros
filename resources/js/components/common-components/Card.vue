<template>
<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8 my-2">
        <div class="card">
            <div class="card-header">{{ titulo }}</div>

            <div class="card-body">
                <slot name="body"></slot>
            </div>

            <div class="card-footer">
                <slot name="footer"></slot>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    props: {
        titulo: {
            type: String,
            required: true
        }
    }
}
</script>
