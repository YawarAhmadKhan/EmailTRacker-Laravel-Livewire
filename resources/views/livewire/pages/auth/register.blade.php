<?php
   use App\Models\User;
   use Illuminate\Auth\Events\Registered;
   use Illuminate\Support\Facades\Auth;
   use Illuminate\Support\Facades\Hash;
   use Illuminate\Validation\Rules;
   
   use function Livewire\Volt\layout;
   use function Livewire\Volt\rules;
   use function Livewire\Volt\state;
   
   layout('layouts.guest');
   
   state([
       'name' => '',
       'email' => '',
       'password' => '',
       'password_confirmation' => ''
   ]);
   
   rules([
       'name' => ['required', 'string', 'max:255'],
       'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
       'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
   ]);
   
   $register = function () {
       $validated = $this->validate();
   
       $validated['password'] = Hash::make($validated['password']);
   
       event(new Registered($user = User::create($validated)));
   
       Auth::login($user);
   
       $this->redirect(route('dashboard', absolute: false), navigate: true);
   };
   
   ?>
<div>
   <div class="wrapper">
      <section class="login-content">
         <div class="container h-100">
            <div class="row align-items-center justify-content-center h-100">
               <div class="col-12">
                  <div class="row align-items-center">
                     <div class="col-lg-6 ">
                        <h2 class="mb-2">Sign Up</h2>
                        <p>Enter your personal details and start journey with us.</p>
                        <form wire:submit="register">
                           <div class="row">
                              <div class="col-lg-12">
                                 <div class="floating-label form-group">
                                    <input wire:model="name" id="name" class="floating-input form-control" type="text" placeholder="Full Name"  name="name" required autofocus autocomplete="name">
                                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
                                    <label>Full Name</label>
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="floating-label form-group">
                                    <input  wire:model="email" id="email" class="floating-input form-control" type="email" placeholder="Email" name="email" required autocomplete="username" >
                                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                                    <label>Email</label>
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="floating-label form-group">
                                    <input wire:model="password" id="password" class="floating-input form-control"type="password"
                                       name="password"
                                       required autocomplete="new-password" placeholder="Password">
                                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                                    <label>Password</label>
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="floating-label form-group">
                                    <input wire:model="password_confirmation" id="password_confirmation" class="floating-input form-control"type="password"
                                       name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
                                    <label for="password_confirmation">Confirm Password</label>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                                    <label class="custom-control-label" for="customCheck1">I agree with the terms of use</label>
                                 </div>
                              </div>
                           </div>
                           <button type="submit" class="btn btn-primary">Sign Up</button>
                           <div class="flex items-center justify-end mt-4">
                              <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}" wire:navigate>
                              {{ __('Already registered?') }}
                              </a>
                           </div>
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
