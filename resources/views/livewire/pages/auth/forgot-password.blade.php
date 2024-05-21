<?php

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\layout;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;

layout('layouts.guest');

state(['email' => '']);

rules(['email' => ['required', 'string', 'email']]);

$sendPasswordResetLink = function () {
    $this->validate();

    // We will send the password reset link to this user. Once we have attempted
    // to send the link, we will examine the response then see the message we
    // need to show to the user. Finally, we'll send out a proper response.
    $status = Password::sendResetLink(
        $this->only('email')
    );

    if ($status != Password::RESET_LINK_SENT) {
        $this->addError('email', __($status));

        return;
    }

    $this->reset('email');

    Session::flash('status', __($status));
};

?>

<div>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="wrapper">
        <section class="login-content">
           <div class="container h-100">
              <div class="row align-items-center justify-content-center h-100">
                 <div class="col-12">
                    <div class="row align-items-center">
                       <div class="col-lg-6 ">
                          <h2 class="mb-2">Reset Password</h2>
                          <p>Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
                          <form wire:submit="sendPasswordResetLink">
                             <div class="row">
                                <div class="col-lg-12">
                                   <div class="floating-label form-group">
                                      <input wire:model="email" id="email" class="floating-input form-control" name="email" required autofocus type="email" placeholder="Enter your Email">
                                      <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                                      <label>Email</label>
                                   </div>
                                </div>
                             </div>
                             <button type="submit" class="btn btn-primary">Reset</button>
                          </form>
                       </div>
                       <div class="col-lg-6 rmb-30">
                        <img src="{{asset('assets/images/login/01.png')}}" class="img-fluid w-80" alt="">
                     </div>
                       
                    </div>
                 </div>
              </div>
           </div>
        </section>
        </div>
</div>
