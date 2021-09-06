<template>
<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8 my-2">
        <div class="card">
            <div class="card-header">
                <slot name="header" />
            </div>

            <div class="card-body">
                <slot name="body" />
            </div>

            <div class="card-footer">
                <slot name="footer" />
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
   
}
</script>
