<template>
<div class="modal fade" :id="modalId" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ titulo }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <slot name="body"></slot>
            <slot name="alert"></slot>
        </div>
        <div class="modal-footer">
            <slot name="footer"></slot>
        </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    props: {
        modalId: {
            type: String,
            required: true
        },
        titulo: {
            type: String,
            required: true
        }
    }
}
</script>
