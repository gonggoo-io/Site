<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

function submit() {
  form.post('/signup', {
    onSuccess: () => alert('회원가입 완료')
  })
}
</script>
