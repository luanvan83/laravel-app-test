<script setup lang="ts">
import { Form, Field, type FormActions } from 'vee-validate';
import * as Yup from 'yup';

import { useUsersStore } from '@/stores/users.store';
import { useAlertStore } from '@/stores/alert.store';
import router from '@/router/index';
import type { IRegisterDto } from '@/interfaces/user-register';
import ValidationError from '@/errors/validation-error';
import { setFieldErrors } from '@/helpers/helpers';

const schema = Yup.object().shape({
    name: Yup.string()
        .required('Name is required'),
    email: Yup.string()
        .required('Email is required'),
    password: Yup.string()
        .required('Password is required')
        .min(8, 'Password must be at least 8 characters'),
    password_confirmation: Yup.string()
        .required('Password confirmation is required')
        .min(8, 'Password confirmation must be at least 8 characters')
});

async function onSubmit(values : IRegisterDto, actions : any) {
    const usersStore = useUsersStore();
    const alertStore = useAlertStore();
    try {
        await usersStore.register(values);
        await router.push('/login');
        alertStore.success('Registration successful');
    } catch (error : any) { 
        console.log('RegisterViewError', error);
        setFieldErrors(actions, error);
    }
}
</script>

<template>
    <div class="col-sm-8 offset-sm-2 mt-5">
        <div class="card m-3">
            <h4 class="card-header">Register</h4>
            <div class="card-body">
                <Form @submit="onSubmit" :validation-schema="schema" v-slot="{ errors, isSubmitting }">
                    <div class="mb-3">
                        <label>Name</label>
                        <Field name="name" type="text" class="form-control" :class="{ 'is-invalid': errors.name }" />
                        <div class="invalid-feedback">{{ errors.name }}</div>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <Field name="email" type="text" class="form-control" :class="{ 'is-invalid': errors.email }" />
                        <div class="invalid-feedback">{{ errors.email }}</div>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <Field name="password" type="password" class="form-control" :class="{ 'is-invalid': errors.password }" />
                        <div class="invalid-feedback">{{ errors.password }}</div>
                    </div>
                    <div class="mb-3">
                        <label>Password confirmation</label>
                        <Field name="password_confirmation" type="password" class="form-control" :class="{ 'is-invalid': errors.password_confirmation }" />
                        <div class="invalid-feedback">{{ errors.password_confirmation }}</div>
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-primary" :disabled="isSubmitting">
                            <span v-show="isSubmitting" class="spinner-border spinner-border-sm mr-1"></span>
                            Register
                        </button>
                        <router-link to="login" class="btn btn-link">Cancel</router-link>
                    </div>
                </Form>
            </div>
        </div>
    </div>
</template>
