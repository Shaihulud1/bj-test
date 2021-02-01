$( document ).ready(function() {
    $('.toast').toast({
        delay: 3000
    })
});

function openTaskModal(action="create", id="", name="", email="", text="", isDone="false", title="Новая задача") {
    modal.email = email
    modal.id = id 
    modal.name = name
    modal.text = text
    modal.isDone = isDone
    modal.action = action
    modal.title = title
    $('#exampleModal').modal('show');
}



if (document.getElementById('todo-list')) {
    var todo = new Vue({
        el: "#todo-list",
        data: {
            tasks: [],
            page: 1,
            sortDir: 'asc',
            sortField: 'name',
        },
        methods: {
            sorting: async function (field) {   
                this.sortField = field
                this.sortDir = this.sortDir == 'asc' ? 'desc' : 'asc'
                await this.getTodoList(this.page)
            },
            getTodoList: async function(page = 1) {
                let url = "/todo/list?page=" + page
                url += "&sortDir=" + this.sortDir + "&sortField=" + this.sortField
                this.page = page
                let res = await fetch(url)
                this.tasks = await res.json()
                
            },
            openTaskModal: function(id, name, email, text, isDone="false")
            {
                console.log(id)
                openTaskModal("update", id, name, email, text, isDone, 'Изменить задачу')
            }
        },
        mounted: function() {
            this.getTodoList()
        }
    })
}

if (document.getElementById('exampleModal')) {
    var modal = new Vue ({
        el: "#exampleModal",
        data: {
            id: "",
            title: "Новая задача",
            name: "",
            email: "",
            text: "",
            isDone: "false",
            action: "create",
            errors: [],
        },
        methods: {
            save: async function() {
                this.errors = []
                let formData = new FormData()
                formData.append("name", this.name)
                formData.append("email", this.email)
                formData.append("text", this.text)
                formData.append("isDone", this.isDone)
                let url = ""
                if (this.action == 'update') {
                    formData.append("id", this.id)
                    url = "/todo/update"
                } else {
                    url = "/todo/create"
                }
                
                let response = await fetch(url, {
                    method: 'POST',
                    body: formData 
                });
                response = await response.json()
                

                if (response.errors.length > 0) {
                    response.errors.forEach(element => {
                        this.errors.push(element)
                    });
                } else {
                    await $('#exampleModal').modal('hide');
                    await $('.toast').toast('show')
                    await todo.getTodoList()
                }

            }
        },
    });
}

if (document.getElementById('loginForm')) {
    var login = new Vue ({
        el: "#loginForm",
        data: {
            name: "",
            pass: "",
            errors: [],
        },
        methods: {
            login: async function(e) {
                e.preventDefault()
                this.errors = []
                let formData = new FormData()
                formData.append("name", this.name)
                formData.append("pass", this.pass)
                let response = await fetch("/auth/login", {
                    method: 'POST',
                    body: formData 
                });
                response = await response.json()
                if (response.errors.length > 0) {
                    response.errors.forEach(element => {
                        this.errors.push(element)
                    });
                } else {
                    window.location.href = '/';
                }
                
            }
        }
    })
}