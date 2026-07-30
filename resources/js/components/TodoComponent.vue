<template>
    <div>
        <div class="col-inside-lg decor-default chat oh-on" v-bind:class="{'d-none' : isVisited()}"  tabindex="5000">
            <div class="chat-body">
                <div class="answer right">
                    <div class="avatar">
                        <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="User name">
                        <div class="status offline"></div>
                    </div>
                    <div class="name">Nikolay Todorov</div>
                    <div class="text">
                        Hey, Welcome to my simple Todo application. Do you want to <a href="#" v-on:click="showTodoList">create a new task</a>
                        or see <a href="#" v-on:click="showTodoList">your board</a>?
                    </div>
                    <div class="time">just now</div>
                </div>
            </div>
        </div>
        <div id="main-todo" class="d-none">
            <input type="hidden" id="filter-status" value=""/>
            <form @submit.prevent="saveData">
                <span class="text-danger " style="font-size:20px;display: block" v-if="form.errors.has('title')" v-text="form.errors.get('title')"></span>
                <span class="text-danger " style="font-size:20px;" v-if="form.errors.has('description')" v-text="form.errors.get('description')"></span>
                <div class="input-group">
                    <input v-model="form.title" :class="{'is-invalid' : form.errors.has('title')}"  placeholder="title"
                           type="text" class="form-control form-control-lg"  @keydown="form.errors.clear('title')"
                           aria-label="title" aria-describedby="button-addon2">
                </div>
                <div class="input-group">
                    <textarea v-model="form.description" placeholder="description" :class="{'is-invalid' : form.errors.has('description')}"
                              type="text" class="form-control form-control-lg"  @keydown="form.errors.clear('description')"
                              aria-label="description" aria-describedby="button-addon2"></textarea>
                </div>
                <div class="input-group" style="margin-top: 20px">
                    <div class="col-sm-8">
                        <button class="btn btn-primary" type="submit" id="button-addon2">Add this to your list</button>
                    </div>
                    <div class="col-sm-4">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Filter by status
                            </button>
                            <div class="dropdown-menu  dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#" v-on:click="getTodos('')">All</a>
                                <a class="dropdown-item" href="#" v-on:click="getTodos('To Do')">To Do</a>
                                <a class="dropdown-item" href="#" v-on:click="getTodos('Completed')">Completed</a>
                            </div>
                        </div>
                    </div>
                </div>
              </form>
            <hr/>
            <div class="w-100 todo">
                <div v-for="(todo, index)  in todos.data" :key="todo.id" class="w-100 d-flex align-items-center p-3 bg-white border-bottom">
                    <span class="mr-2">
                       <svg v-if="isCompleted(todo)" v-on:click="toggleTodo(todo, index)" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle" width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#FFC107" fill="none" stroke-linecap="round" stroke-linejoin="round">
                         <title>Click to set for "To Do"</title>
                           <path stroke="none" d="M0 0h24v24H0z"/>
                          <circle cx="12" cy="12" r="9" />
                       </svg>
                       <svg v-if="isToDo(todo)" v-on:click="toggleTodo(todo, index)" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-check" width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5" stroke="#4CAF50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <title>Click to set to "Completed"</title>
                           <path stroke="none" d="M0 0h24v24H0z"/>
                          <circle cx="12" cy="12" r="9" />
                          <path d="M9 12l2 2l4 -4" />
                       </svg>
                    </span>
                    <div  class="font-weight-bolder todo-item">
                        <span v-bind:class="{'completed' : isCompleted(todo)}">{{ todo.title }}</span>
                        <span v-bind:class="{'completed' : isCompleted(todo)}">{{ todo.description }}</span>
                    </div>
                    <div class="ml-auto mr-2 d-flex align-items-center">
                       <span>
                            {{ todo.status}}
                       </span>
                    </div>
            </div>
        </div>
        <div class="input-group">
            <button v-if="hasPrev"  v-on:click="prev" class="btn btn-primary mt-20">< </button>&nbsp
            <button v-if="hasNext"  v-on:click="next" class="btn btn-primary mt-20">> </button>
        </div>
    </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                todos: '',
                form: new Form({
                    title: '',
                    description: ''
                })
            }
        },
        methods: {
            toggleTodo(todo, index) {
                let data = new FormData();
                data.append('_method', 'PATCH');
                let todo_status = '';
                if (todo.status != 'To Do') {
                     todo_status = 'To Do';
                } else {
                     todo_status = 'Completed';
                }
                data.append('status', todo_status);
                data.append('title', todo.title);
                data.append('description', todo.description);
                axios.post('/api/todos/' + todo.id, data).then((res) => {
                    Vue.set(this.todos.data, index, {
                        'description': todo.description,
                        'title': todo.title,
                        'status': todo_status,
                        'id': todo.id
                    });
                }).catch((error) => {
                    this.form.errors.record(error.response.data.errors)
                })
            },
            getTodos(status = "") {
                let uri = '/api/todos';
                if (status != '') {
                    uri += '?status=' + status;
                    $('#dropdownMenuButton').text(status);
                } else {
                    $('#dropdownMenuButton').text('All');
                }
                $('#filter-status').val(status);
                if (this.isVisited()) {
                    $('.chat').addClass('d-none');
                    $('#main-todo').removeClass('d-none');
                }
                axios.get(uri).then((res) => {
                    this.todos = res.data
                }).catch((error) => {
                    console.log(error)
                })
            },
            next() {
                if (this.todos.links != null && this.todos.links.next != null) {
                    let uri = this.todos.links.next + '&status=' + $('#filter-status').val()
                    axios.get(uri).then((res) => {
                        this.todos = res.data;
                    }).catch((error) => {
                        console.log(error)
                    })
                }
            },
            prev() {
                if (this.todos.links != null && this.todos.links.prev != null) {
                    let uri = this.todos.links.prev + '&status=' + $('#filter-status').val()
                    axios.get(uri).then((res) => {
                        this.todos = res.data;
                    }).catch((error) => {
                        console.log(error)
                    })
                }
            },
            saveData() {
                let data = new FormData();
                data.append('title', this.form.title)
                data.append('description', this.form.description)
                axios.post('/api/todos', data).then((res) => {
                    this.form.reset()
                    this.getTodos()
                }).catch((error) => {
                    this.form.errors.record(error.response.data.errors)
                })
            },
            isCompleted(todo) {
                return todo.status == 'Completed';
            },
            isToDo(todo) {
                return todo.status == 'To Do';
            },
            showTodoList() {
                localStorage.setItem('is_visited', 'yes');
                if (this.isVisited()) {
                    $('.chat').addClass('d-none');
                    $('#main-todo').removeClass('d-none');
                }
            },
            isVisited() {
                let is_visited = localStorage.getItem('is_visited');
                return is_visited == 'yes';
            }
        },
        computed: {
            hasPrev() {
                return this.todos?.links?.prev != null
            },
            hasNext() {
                return this.todos?.links?.next != null
            },
        },
        mounted() {
            this.getTodos('')
        }
    }
</script>
