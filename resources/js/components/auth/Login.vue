<template>
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Login</div>

                <div class="card-body">
                    <form :method="method" action="" @submit.prevent="login($event)">

                        <input type="hidden" name="_token" :value="csrf_token">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" v-model="email" required autocomplete="email" autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Senha</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" v-model="password" required autocomplete="current-password">
                            </div>
                        </div>
                            
                        <div class="form-group row" v-if="erro">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <label class="form-check-label text-danger" for="remember">
                                        {{ erro }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">

                                    <label class="form-check-label" for="remember">
                                        Mantenhame conectado
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>
                                <a class="btn btn-link" href="">
                                    Esqueci a senha
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<script>
    export default {
        props: [
            'csrf_token',
        ],
        data() {
            return {
                url: 'http://localhost:8000/api/login',
                method: 'POST',
                email: null,
                password: null,
                erro: null,
            }
        },
        methods: {
            login(e) {
                const myHeaders = new Headers({
                    "Accept": "application/json",
                    "Content-Type": "application/x-www-form-urlencoded",
                });

                const urlencoded = new URLSearchParams({
                    "email": this.email,
                    "password": this.password
                });

                const requestOptions = {
                method: this.method,
                headers: myHeaders,
                body: urlencoded,
                redirect: 'follow'
                };

                fetch(this.url, requestOptions)
                .then(response => response.json())
                .then(result => {
                    if (result.error) this.erro = result.error;
                    if (result.token) {
                        this.token = result.token;
                        document.cookie = `token=${result.token};SameSite=Lax`;
                        e.target.submit()
                    }
                })
                .catch(error => console.log('error', error));
            }
        },
    }
</script>
