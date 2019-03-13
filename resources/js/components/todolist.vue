<template>
    <div >
        <form @submit.prevent="AddToDo">
        <input v-model="inputtitle">
        <input v-model="inputinfo">
        <button type="submit">Eyo</button>
    </form>

    <ul v-for="todo in todos" :key="todo.id">
        <li @dblclick="edittodo(todo)" v-if="!todo.editing">{{todo.title}}</li>
        <input v-model="todo.title" v-if="todo.editing" @blur="doneedit(todo)" @keyup.enter="doneedit(todo)" @keyup.esc="canceledit(todo)"  v-focus>
        <li @dblclick="edittodoinfo(todo)" v-if="!todo.editinginfo">{{todo.info}}</li>
        <input v-model="todo.info" v-if="todo.editinginfo" @blur="doneeditinfo(todo)" @keyup.enter="doneeditinfo(todo)" @keyup.esc="canceleditinfo(todo)"  v-focus>
        <div @click="DeleteToDo(todo)">&times</div>
    </ul>
    </div>

</template>
<script>
    export default {
        data(){
            return {
                inputtitle: '',
                inputinfo: '',
                editing: false,
                editinginfo: false,
                completed: false,

                todos: []


            }
        },
        directives: {
            focus: {
                // directive definition
                inserted: function (el) {
                    el.focus()
                }
            }
        },

        methods: {

            edittodo(todo) {
                this.beforeEditCache = todo.title
                todo.editing = true


            },

            AddToDo(todo){
                axios.post('./api/todo', {title: this.inputtitle, info: this.inputinfo, completed: this.completed, editing: this.editing, editinginfo: this.editinginfo})
                axios.get('./api/todo').then(response => this.todos = response.data)
                this.inputtitle= ''
                this.inputinfo= ''
            },




            edittodoinfo(todo){
                this.beforeEditCacheinfo = todo.info
                todo.editinginfo = true
            },

            doneedit(todo) {
                if (todo.title.trim() == '') {
                    todo.title = this.beforeEditCache
                }
                todo.editing = false
                axios.put('./api/todo/' + todo.id, {title: todo.title,})
            },

            doneeditinfo(todo){
                if (todo.info.trim() == '') {
                    todo.info = this.beforeEditCacheinfo
                }
                todo.editinginfo = false
                axios.put('./api/todo/' + todo.id, {info: todo.info,})
            },
            canceledit(todo) {
                todo.title = this.beforeEditCache
                todo.editing = false
            },

            canceleditinfo(todo){
                todo.info = this.beforeEditCacheinfo
                todo.editinginfo = false
            },

            DeleteToDo(todo){
                axios.delete('./api/todo/' + todo.id)
                axios.get('./api/todo').then(response => this.todos = response.data)

            }

        },

        created(){
            axios.get('./api/todo').then(response => this.todos = response.data)
        }


    }
</script>
<style>
    @import url('https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900');

</style>