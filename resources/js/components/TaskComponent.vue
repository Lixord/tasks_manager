<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>To-Do List</h1>
            </div>
        </div>
        <form @submit.prevent="addTask">
            <div class="row">
                <div class="col-8">
                    <input class="form-control" v-model="newTask" placeholder="New Task" required>
                </div>
                <div class="col-4">
                    <button type="submit" class="btn btn-primary">Add Task</button>
                </div>
            </div>
        </form>

        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="task in tasks" :key="task.id" :style="{ textDecoration: task.is_completed ? 'line-through' : 'none' }">
                    <th scope="row">{{ task.id }}</th>
                    <td>{{ task.title }}</td>
                    <td><button class="btn btn-danger" @click="deleteTask(task.id)">Delete</button></td>
                    <td><button class="btn btn-success" @click="completeTask(task.id)" v-if="!task.is_completed">Complete</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            tasks: [],
            newTask: ''
        };
    },
    mounted() {
        this.getTasks();
        this.pollInterval = setInterval(this.getTasks, 3000);
    },
    beforeDestroy() {
        clearInterval(this.pollInterval);
    },
    methods: {
        async getTasks() {
            let response = await axios.get('/api/tasks');
            this.tasks = response.data;
        },
        async addTask() {
            let response = await axios.post('/api/tasks', { title: this.newTask });
            this.tasks.push(response.data.task);
            this.newTask = '';
        },
        async deleteTask(id) {
            await axios.delete(`/api/tasks/${id}`);
            this.tasks = this.tasks.filter(task => task.id !== id);
        },
        async completeTask(id) {
            let response = await axios.put(`/api/tasks/${id}`);
            let task = this.tasks.find(task => task.id === id);
            task.is_completed = response.data.is_completed;
        }
    }
}
</script>
