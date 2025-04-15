<template>
    <div class="max-w-xl mx-auto mt-10">
        <div
            v-if="message"
            class="bg-green-100 text-green-800 p-2 rounded mb-4"
        >
            {{ message }}
        </div>

        <form @submit.prevent="addTask" class="flex gap-2 mb-4">
            <input
                v-model="newTask"
                class="border p-2 flex-1"
                placeholder="Add new task"
            />
            <button
                type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded"
            >
                Add
            </button>
        </form>

        <ul class="space-y-2">
            <li
                v-for="task in tasks"
                :key="task.id"
                class="flex items-center gap-4 border p-2 rounded w-full"
            >
                <span class="w-[90%]">
                    <span
                        v-if="!task.isEditing"
                        :class="{
                            'line-through text-gray-500': task.is_completed,
                        }"
                        @dblclick="editTask(task)"
                    >
                        {{ task.title }}
                    </span>
                    <input
                        v-else
                        v-model="task.title"
                        @keyup.enter="saveTask(task)"
                        @blur="saveTask(task)"
                        class="border p-1 w-full"
                    />
                </span>
                <div class="w-[10%] space-x-2">
                    <button @click="toggleTask(task)" class="text-green-600">
                        ✅
                    </button>
                    <button @click="deleteTask(task)" class="text-red-600">
                        ❌
                    </button>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            tasks: [],
            newTask: "",
            message: "",
        };
    },
    mounted() {
        this.fetchTasks();
    },
    methods: {
        async fetchTasks() {
            const res = await axios.get("/api/tasks");
            this.tasks = res.data.data.map((task) => ({
                ...task,
                isEditing: false,
            }));
        },
        async addTask() {
            if (!this.newTask.trim()) return;
            const res = await axios.post("/api/tasks", { title: this.newTask });
            this.newTask = "";
            this.showMessage(res.data.message);
            this.fetchTasks();
        },
        async toggleTask(task) {
            const res = await axios.put(`/api/tasks/${task.id}`, {
                is_completed: !task.is_completed,
            });
            this.showMessage(res.data.message);
            this.fetchTasks();
        },
        async deleteTask(task) {
            const confirmed = window.confirm(
                `Are you sure you want to delete the task: "${task.title}"?`
            );
            if (!confirmed) return;

            const res = await axios.delete(`/api/tasks/${task.id}`);
            this.showMessage(res.data.message);
            this.fetchTasks();
        },
        editTask(task) {
            task.isEditing = true;
        },
        async saveTask(task) {
            if (!task.title.trim()) {
                this.showMessage("Task title cannot be empty.");
                return;
            }
            task.isEditing = false;
            const res = await axios.put(`/api/tasks/${task.id}`, {
                title: task.title,
            });
            this.showMessage(res.data.message);
            this.fetchTasks();
        },
        showMessage(msg) {
            this.message = msg;
            setTimeout(() => {
                this.message = "";
            }, 3000);
        },
    },
};
</script>
