

<div class="container content-wrapper" id="loginForm">
    <form class="task-update" @submit="login">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Имя пользователя" v-model="name">
        </div>
        <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Пароль" v-model="pass">
        </div>
        <div class="alert alert-danger" role="alert" v-for="error in errors">
            {{error}}
        </div>
        <button type="submit" class="btn btn-primary">Войти</button>
    </form>
</div>
