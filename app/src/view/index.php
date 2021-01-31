
    <div class="container content-wrapper" id="todo-list">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">
                        <a class="sort pointer" @click="getTodoList(page, 'name')">имя пользователя</a>
                    </th>
                    <th scope="col">
                        <a class="sort pointer" @click="getTodoList(page, 'email')">email</a>
                    </th>
                    <th scope="col">
                        <a class="sort pointer" @click="getTodoList(page, 'text')">текст</a>
                    </th>
                    <th scope="col">
                        <a class="sort pointer" @click="getTodoList(page, 'isDone')">статус</a>    
                    </th>
                    <?if ($_SESSION['role'] == 'admin') {?>
                        <th scope="col"></th>
                    <? } ?>
                </tr>
            </thead>
            <tbody>
                <tr v-for="task in tasks.items">
                    <th>{{task.name}}</th>
                    <td>{{task.email}}</td>
                    <td>{{task.text}}</td>
                    <td>
                        <span class='badge bg-success' v-if="task.isDone">Сделано</span>
                        <span class='badge bg-warning' v-if="task.updatedByAdmin">Отредактировано администратором</span>
                    </td>
                    <?if ($_SESSION['role'] == 'admin') {?>
                        <td>
                            <a @click="openTaskModal(task.id, task.name, task.email, task.text, task.isDone)" class="btn btn-primary btn-sm">Изменить</a>
                        </td>
                    <? } ?>
                </tr>
            </tbody>
        </table>
        <a class="btn btn-primary btn-sm" onclick="openTaskModal()">Создать задачу</a>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li v-for="index in tasks.pages" :class="{'active' : index == page}" class="page-item">
                    <a @click="getTodoList(index)" class="page-link pointer" >{{index}}</a>
                </li>
            </ul>
        </nav>
    </div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{title}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form>
                <div class="form-group modal-group">
                    <label for="recipient-name" class="col-form-label">имя пользователя:</label>
                    <input type="text" class="form-control" id="recipient-name" v-model="name">
                </div>
                <div class="form-group modal-group">
                    <label for="recipient-name" class="col-form-label">email:</label>
                    <input type="text" class="form-control" id="recipient-name" v-model="email">
                </div>
                <div class="form-group modal-group">
                    <label for="message-text" class="col-form-label">текст:</label>
                    <textarea class="form-control" id="message-text" v-model="text"></textarea>
                </div>
                <?if ($_SESSION['role'] == 'admin') {?>
                    <div class="form-group modal-group">
                        <select name="isDone" v-model="isDone"> <!--Supplement an id here instead of using 'name'-->
                            <option value="false">В работе</option>
                            <option value="true">Сделано</option>
                        </select>
                    </div>
                <?}?>
            </form>
        </div>
        <div class="alert alert-danger" role="alert" v-for="error in errors">
            {{error}}
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="save">Сохранить</button>
        </div>
        </div>
    </div>
</div>


