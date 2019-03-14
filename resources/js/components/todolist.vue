<template>
    <div >
        <form @submit.prevent="AddToDo">
            <div class="inputs" v-if="Hidden" v-focus @keyup.esc="canceladd(todo)">
                <input v-model="inputtitle" class="inputtitle" placeholder="Vul hier uw ToDo in">
                <input v-model="inputinfo" class="inputinfo" placeholder="Vul hier de beschrijving in">
                <input type="file" class="inputimage" @change="OnImageChange">
                <button type="submit" class="submit">Eyo</button>
            </div>
        </form>
    <div class="boxes">
        <h3 class="bigtext">ToDo List</h3>
        <h3 class="addtodo" @click="AddToDoScreen(todo)">+</h3>
    <ul v-for="todo in todos" :key="todo.id" >
        <div class="doosjes">
            <div @click="DeleteToDo(todo)" class="deletebtn">&times</div>
            <input type="checkbox" class="checkbox" v-model="todo.completed" @click="CompletedData(todo)" >
            <li @dblclick="edittodo(todo)" v-if="!todo.editing" :class="{ completed: todo.completed }" class="title">{{todo.title}}</li>
            <input v-model="todo.title" v-if="todo.editing" @blur="doneedit(todo)" @keyup.enter="doneedit(todo)" @keyup.esc="canceledit(todo)"  v-focus class="titleedit">
            <li @dblclick="edittodoinfo(todo)" v-if="!todo.editinginfo" :class="{ completed: todo.completed }" class="info">{{todo.info}}</li>
            <input v-model="todo.info" v-if="todo.editinginfo" @blur="doneeditinfo(todo)" @keyup.enter="doneeditinfo(todo)" @keyup.esc="canceleditinfo(todo)"  v-focus class="infoedit">
            <img :src="'./img/' + todo.image " class="image">
        </div>
    </ul>
    </div>
    </div>

</template>
<script>
    export default {
        data(){
            return {
                inputtitle: '',
                Hidden: false,
                inputinfo: '',
                image: '',
                editing: false,
                editinginfo: false,
                completed: 0,
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

            AddToDoScreen(todo){
                if(this.Hidden === true){
                    this.Hidden = false
                }else{
                    this.Hidden = true
                }

            },

            canceladd(todo){
                this.Hidden = false
            },

            AddToDo(todo){
                axios.post('./api/todo', {title: this.inputtitle, info: this.inputinfo, completed: this.completed, editing: this.editing, editinginfo: this.editinginfo, image: this.image})
                axios.get('./api/todo').then(response => this.todos = response.data)
                this.inputtitle= ''
                this.inputinfo= ''
                this.Hidden = false
            },

            OnImageChange(e){

                var fileReader = new FileReader()

                fileReader.readAsDataURL(e.target.files[0])

                fileReader.onload = (e) =>{
                    this.image = e.target.result
                    console.log(e)
                }





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

            },

            CompletedData(todo){
                if(todo.completed == 0){
                    todo.completed = 1
                    axios.put('./api/todo/' + todo.id, {completed: todo.completed,})
                }else{
                    todo.completed = 0
                    axios.put('./api/todo/' + todo.id, {completed: todo.completed,})
                }

            }

        },

        created(){
            axios.get('./api/todo').then(response => this.todos = response.data)
        }


    }
</script>
<style>
    @import url('https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900');

    html{
        font-family: Catamaran;
        margin: 0;
        padding: 0;
        height: 100vh;
        width: 100vw;
        background-color: rgb(239, 50, 50)
    }

    li{
        list-style-type: none;
        text-decoration: none;
    }

    .addtodo{
        cursor: pointer;
        float: right;
        transform: translate(-50%,-50%);
        z-index: 1;
        color: white;
        margin-top: 40px;
        margin-right: 10px;
        font-size: 25px;



    }

    .image{
        
    }




    .boxes{
        width: 600px;
        height: 800px;
        border: 0.5px solid transparent;
        border-radius: 10px;
        background-color: rgb(73,73,73);
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
        overflow-y: auto;
        z-index: 0;
    }

    ::-webkit-scrollbar{
        width: 50px;
        color: black;
    }

    .bigtext{
        color: rgb(239, 50, 50);
        float: left;
        margin-left: 10px;
        font-size: 25px;
    }

    .doosjes{
        position: relative;
        width: 500px;
        height: 150px;
        border-radius: 10px;
        float: left;
        overflow: hidden;
        border: 1px solid black;
        margin-bottom: 10px;
    }

    .checkbox{
        width: 1.3em;
        height: 1.3em;
        background-color: transparent;
        border-radius: 50%;
        vertical-align: middle;
        border: 1px solid rgb(239,50,50);
        -webkit-appearance: none;
        outline: none;
        cursor: pointer;
        margin-top: 20px;
    }

    .checkbox:checked {
        background-color: rgb(239,50,50);
    }



    .deletebtn{
        position: absolute;
        cursor: pointer;
        font-size: 40px;
        float: right;
        color: rgb(239,50,50);
        top: 2%;
        right: 5%;
    }


    .completed{
        text-decoration: line-through;
        color: rgb(239,50,50) !important;
    }

    div{
        display: block;
    }

    .inputs{
        position: absolute;
        width: 400px;
        height: 250px;
        background-color: rgb(95,95,95);
        border-radius: 10px;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
        z-index: 1;
    }

    .inputtitle{
        position: absolute;
        top: 10%;
        left: 50%;
        transform: translate(-50%,-50%);
        background-color: white;
        padding: 10px;
        border: none;
        outline: none;
        color: black;
        margin: 3px;
        font-size: 15px;
        border-radius: 10px;

    }

    .inputinfo{
        position: absolute;
        top: 30%;
        left: 50%;
        transform: translate(-50%,-50%);
        background-color: white;
        padding: 10px;
        border: none;
        outline: none;
        color: black;
        margin: 3px;
        font-size: 15px;
        border-radius: 10px;

    }

    .inputimage{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
        background-color: white;
        padding: 10px;
        border: none;
        outline: none;
        color: black;
        margin: 3px;
        font-size: 15px;
        border-radius: 10px;

    }

    .submit{
        position: absolute;
        cursor: pointer;
        bottom: 5%;
        left: 50%;
        transform: translate(-50%,-50%);
        background-color: white;
        padding: 10px;
        border: none;
        outline: none;
        color: black;
        margin: 3px;
        font-size: 15px;
        border-radius: 10px;

    }

    .title{
        position: absolute;
        margin-top: 2px;
        text-align: center;
        font-size: 20px;
        top: 20%;
        left: 50%;
        transform: translate(-50%,-50%);
        color: white;
    }

    .titleedit{
        position: relative;
        text-align: center;
        font-size: 18px;
        left: 45%;
        width: fit-content;
        top: 20%;
        transform: translate(-50%,-50%);
        color: black;
    }

    .info{
        position: absolute;
        text-align: center;
        font-size: 18px;
        left: 50%;
        top: 50%;
        transform: translate(-50%,-50%);
        color: white;
    }

    .infoedit{
        position: absolute;
        text-align: center;
        font-size: 18px;
        left: 50%;
        top: 50%;
        transform: translate(-50%,-50%);
        color: black;

    }

</style>