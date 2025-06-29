<script lang="ts" setup>
import { Card, CardHeader, CardContent } from '@ui/card';
import { Button } from '@ui/button';
import { Input } from '@ui/input';
import { Label } from '@ui/label';
import { useAuthStore } from '@/stores/auth';
import { ref, onMounted } from 'vue';
import { toast } from 'vue-sonner';
import { handleError } from '@/lib/utils';

const authStore = useAuthStore();

const form = ref({
    name: '',
    email: '',
});

const loading = ref(false);

onMounted(async () => {
    try {
        await authStore.fetchUser();
        form.value.name = authStore.user?.name || '';
        form.value.email = authStore.user?.email || '';
    } catch (error) {
        handleError('Error fetch user', error);
    }
});

const updateProfile = async () => {
    loading.value = true;
    try {
        await authStore.updateProfile(form.value);
        toast.success('Profile updated successfully');
    } catch (error) {
        handleError('Error update profile', error);
    } finally {
        loading.value = false;
    }
};

</script>

<template>
    <div class="flex p-6 justify-center">
        <Card class="w-full max-w-4xl">
            <CardHeader>
                <h2 class="text-lg font-semibold">Edit Profile</h2>
                <p class="text-sm text-gray-500">Update your profile information below.</p>
            </CardHeader>
            <CardContent>
                <form @submit.prevent="updateProfile" class="space-y-4">
                    <div class="space-y-2">
                        <Label for="name">Name</Label>
                        <Input 
                            id="name" 
                            v-model="form.name" 
                            type="text" 
                            required 
                            placeholder="Enter your name"
                        />
                    </div>
                    
                    <div class="space-y-2">
                        <Label for="email">Email</Label>
                        <Input 
                            id="email" 
                            v-model="form.email" 
                            type="email" 
                            required 
                            placeholder="Enter your email"
                        />
                    </div>
                    
                    <div class="flex justify-end">
                        <Button 
                            type="submit" 
                            :disabled="loading || authStore.loading"
                        >
                            {{ loading || authStore.loading ? 'Updating...' : 'Update Profile' }}
                        </Button>
                    </div>
                </form>
            </CardContent>
        </Card>
    </div>
</template>