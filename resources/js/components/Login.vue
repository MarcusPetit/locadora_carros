
<template>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">Login (component VUE)</div>

                <div class="card-body">
                    <form method="POST" action="">

                        <div class="row mb-4">
                            <label for="email" class="col-md-5 col-form-label text-md-end">Email</label>

                            <div class="col-md-7">
                                <input id="email" type="email" class="form-control"  value="" required autocomplete="email" autofocus>

                             </div>
                        </div>

                        <div class="row mb-4">
                            <label for="password" class="col-md-5 col-form-label text-md-end">Senha</label>

                            <div class="col-md-7">
                                <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">

                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-7 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">

                                    <label class="form-check-label" for="remember">
                                        Lembrar
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="col-md-9 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                   Login
                                </button>

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
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
