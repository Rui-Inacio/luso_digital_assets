<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-5">
        <h1>Todo List</h1>

        <!-- Create new todo -->
        <div>
          <form class="form-inline">
            <div class="form-group">
              <div class="row">
                <div class="col-9">
                  <input type="text" class="form-control" placeholder="New todo..." v-model="title">
                </div>
                <div class="col">
                  <button class="btn btn-outline-success" @click.prevent="createTodo">
                    <span v-if="loading" class="spinner-border spinner-border-sm"></span>
                    <span v-else>Create</span>
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>

        <div class="row mt-3" v-if="error.show">
          <AlertComponent @close="() => { error.show = false }" :message="error.message" :show="error.show">
          </AlertComponent>
        </div>

        <div v-for="todo in todoList" :key="todo.id" class="m-3">
          <div class="row">
            <div class="col-8">
              <input type="text" class="form-control" v-model="todo.title" @change="() => { updateTodo(todo) }">
            </div>
            <div class="col">
              <div class="btn-group" role="group">
                <button class="mr-2" :class="todo.status == 'pending' ? 'btn btn-secondary' : 'btn btn-primary'"
                  @click="changeStatus(todo)">
                  {{ todo.status }}
                </button>
                <button class="btn btn-outline-danger" type="button" @click="deleteTodo(todo)">
                  <span v-if="todo.loading" class="spinner-border spinner-border-sm"></span>
                  <svg v-else xmlns="http://www.w3.org/2000/svg" height="1em"
                    viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                    <path
                      d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import AlertComponent from './utils/AlertComponent.vue';
const baseUrl = 'http://localhost:8000/api';

export default {
  name: 'TodoList',
  components: {
    AlertComponent,
  },
  data() {
    return {
      title: '',
      status: 'Pending',
      todoList: [],
      loading: false,
      error: {
        message: '',
        show: '',
        type: 'alert alert-danger'
      }
    }
  },
  methods: {

    createTodo() {
      this.loading = true;
      let todo = {
        title: this.title,
        status: 'pending',
      };

      this.validateTodo(todo);
      axios.post(`${baseUrl}/todos`, todo)
        .then(response => {
          console.log(response);
          // this.todoList.unshift(todo);
          this.retrieveTodoList();
          this.title = '';
        })
        .catch(err => {
          console.log(err);
          this.error.message = err.response.data.message;
          this.error.show = true;
        })
        .finally(() => {
          this.loading = false;
        });
    },

    retrieveTodoList() {
      this.loading = true;

      axios.get(`${baseUrl}/todos`)
        .then(response => {
          console.log(response);
          this.todoList = response.data;
        }).catch(error => {
          console.log(error);
        })
        .finally(() => {
          this.loading = false;
        });
    },

    updateTodo(todo) {
      let message = this.validateTodo(todo);
      if (message !== "") {
        return message;
      }

      const id = todo.id;
      todo.loading = true;

      axios.put(`${baseUrl}/todos/${id}`, todo)
        .then(response => {
          console.log(response);
        })
        .catch(error => {
          console.log(error);
          this.error.message = error.response.data.message;
          this.error.show = true;
        }).finally(() => {
          todo.loading = false;
        });
    },

    deleteTodo(todo) {
      const id = todo.id;
      todo.loading = true;

      axios.delete(`${baseUrl}/todos/${id}`)
        .then(response => {
          console.log(response);
          // const index = this.todoList.findIndex(todo => {
          //   return todo.id = id;
          // });
          // this.todoList.splice(index, 1);
          this.retrieveTodoList();
        })
        .catch(err => {
          console.log(err);
          this.error.message = err.response.data.message;
          this.error.show = true;
        })
        .finally(() => {
          todo.loading = false;
        });

    },

    validateTodo(input) {
      if (input.title == null || input.title === '') {
        return "Title is missing";
      }
      if (input.status == null || input.status === '') {
        return "Status is missing";
      }
      return "";
    },

    changeStatus(todo) {
      todo.status === 'pending' ? todo.status = 'done' : todo.status = 'pending';
      this.updateTodo(todo);
    }

  },
  mounted() {
    this.retrieveTodoList();
  }
}
</script>
