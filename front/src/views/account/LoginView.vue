<script setup lang="ts">
import { Form, Field } from 'vee-validate';
import * as Yup from 'yup';

import { useAuthStore } from '@/stores/auth.store';

const schema = Yup.object().shape({
    email: Yup.string().required('Email is required'),
    password: Yup.string().required('Password is required')
});

async function onSubmit(values, actions) {
    
    const authStore = useAuthStore();
    const { email, password } = values;
    try {
        await authStore.login(email, password);
    } catch (error) {
        debugger;
        console.log('LoginViewError', error);
    }
}
</script>

<template>
    <div class="col-sm-8 offset-sm-2 mt-5">
        <div class="card m-3">
            <h4 class="card-header">Login</h4>
            <div class="card-body">
                <Form @submit="onSubmit" :validation-schema="schema" v-slot="{ errors, isSubmitting }">
                    <div class="form-group">
                        <label>Email</label>
                        <Field name="email" type="text" class="form-control" :class="{ 'is-invalid': errors.email }" />
                        <div class="invalid-feedback">{{ errors.email }}</div>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <Field name="password" type="password" class="form-control" :class="{ 'is-invalid': errors.password }" />
                        <div class="invalid-feedback">{{ errors.password }}</div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" :disabled="isSubmitting">
                            <span v-show="isSubmitting" class="spinner-border spinner-border-sm mr-1"></span>
                            Login
                        </button>
                        <router-link to="register" class="btn btn-link">Register</router-link>
                    </div>
                </Form>
            </div>
        </div>
    </div>
</template>
