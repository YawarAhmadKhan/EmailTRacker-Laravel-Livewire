<?php
   use App\Livewire\Forms\LoginForm;
   use Illuminate\Support\Facades\Session;
   
   use function Livewire\Volt\form;
   use function Livewire\Volt\layout;
   
   layout('layouts.guest');
   
   form(LoginForm::class);
   
   $login = function () {
       $this->validate();
   
       $this->form->authenticate();
   
       Session::regenerate();
   
       $this->redirectIntended(default: route('dashboard', absolute: false));
   };
   
   ?>
<div>
   <!-- Favicon -->
   <!-- loader Start -->
   {{-- 
   <div id="loading">
      <div id="loading-center"></div>
   </div>
   --}}
   <!-- loader END -->
   <div class="wrapper">
      <section class="login-content">
         <div class="container h-100">
            <div class="row align-items-center justify-content-center h-100">
               <div class="col-12">
                  <div class="row align-items-center">
                     <div class="col-lg-6">
                        <h2 class="mb-2">Sign In</h2>
                        <p>To keep connected with us, please login with your personal info.</p>
                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />
                        <form wire:submit.prevent="login">
                           <div class="row">
                              <div class="col-lg-12">
                                 <div class="floating-label form-group">
                                    <x-text-input wire:model="form.email" id="email" class="floating-input form-control" type="email" name="email" required autofocus autocomplete="username" />
                                    <x-input-error :messages="$errors->get('form.email')" class="mt-2 text-danger" />
                                    <label>Email</label>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <div class="floating-label form-group">
                                    <input wire:model="form.password" id="password" class="floating-input form-control" type="password" name="password" required autocomplete="current-password">
                                    <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
                                    <label>Password</label>
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="custom-control custom-checkbox mb-3">
                                    <input wire:model="form.remember" id="remember" type="checkbox" class="custom-control-input">
                                    <label class="custom-control-label" for="remember">Remember Me</label>
                                 </div>
                              </div>
                              <div class="col-lg-6 rtl-left">
                                 @if (Route::has('password.request'))
                                 <a href="{{ route('password.request') }}" class="text-primary float-right" wire:navigate>Forgot Password?</a>
                                 @endif
                              </div>
                           </div>
                           <button type="submit" class="btn btn-primary">Sign In</button>
                           <p class="mt-3">
                              Create an Account <a class="text-primary"href="{{ route('register') }}" wire:navigate>Sign Up</a>
                           </p>
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
   <!-- Backend Bundle JavaScript -->
</div>
