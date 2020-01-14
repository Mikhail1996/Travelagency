<div class="auth_block">
    <? if(!is_null($this->muser->id_user)): ?>
        <p>Здравствуйте, <?=$this->muser->user_name?>!</p>
        <p class="exit_account">Выйти</p>
    <? else: ?>
        <a href="/auth/login">Войти</a>
        <a href="/auth/login">Регистрация</a>
    <? endif; ?>
</div>