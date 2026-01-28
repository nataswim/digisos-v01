@extends('layouts.admin')

@section('title', 'Modifier un utilisateur')
@section('page-title', 'Modifier l\'utilisateur')
@section('page-description', 'Modification du compte : ' . $user->name)

@section('content')
<div class="container-fluid">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @method('PUT')
        @include('admin.users.partials.form', [
            'submitLabel' => 'Mettre A jour l\'utilisateur',
            'user' => $user,
            'roles' => $roles
        ])
    </form>
</div>
@endsection

@push('styles')
<style>




.bg-gradient-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #10b981 100%);
}



.bg-gradient-secondary {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation des mots de passe (optionnels en edition)
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    
    function validatePasswords() {
        // Si un mot de passe est saisi, les deux doivent correspondre
        if (passwordInput.value || confirmPasswordInput.value) {
            if (passwordInput.value !== confirmPasswordInput.value) {
                confirmPasswordInput.setCustomValidity('Les mots de passe ne correspondent pas');
            } else {
                confirmPasswordInput.setCustomValidity('');
            }
        } else {
            confirmPasswordInput.setCustomValidity('');
        }
    }
    
    passwordInput.addEventListener('input', validatePasswords);
    confirmPasswordInput.addEventListener('input', validatePasswords);

    // Confirmation de changement de rôle
    const roleSelect = document.getElementById('role_id');
    const originalRole = roleSelect.value;
    
    roleSelect.addEventListener('change', function() {
        if (originalRole && this.value !== originalRole) {
            const confirmed = confirm('Changer le rôle de cet utilisateur peut affecter ses permissions. Continuer ?');
            if (!confirmed) {
                this.value = originalRole;
            }
        }
    });
});
</script>
@endpush