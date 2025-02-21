<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        {{-- <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p> --}}
    </header>

    <div class="col-md-12">
        <!--begin::Quick Example-->
        <div class="card card-primary card-outline mb-4">
            <!--begin::Header-->
            <div class="card-header">
                <div class="card-title">{{ __('Ensure your account is using a long, random password to stay secure.') }}</div>
            </div>
            <!--end::Header-->
            <!--begin::Form-->
            <form method="post" action="{{ route('password.update') }}">
                @csrf
                @method('put')
                <!--begin::Body-->
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label"
                            for="update_password_current_password">{{ __('Current Password') }}</label>
                        <input id="update_password_current_password" name="current_password" type="password"
                            autocomplete="current-password" class="form-control">
                            @php
                            $messages = $errors->updatePassword->get('current_password');
                            @endphp
                        @if ($messages)
                            <ul style="color:red;">
                                @foreach ((array) $messages as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                        @endif

                    </div>
                    <div class="mb-3">
                        <label for="update_password_password" class="form-label">{{ __('New Password') }}</label>
                        <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                            class="form-control">
                            @php
                            $messages_password = $errors->updatePassword->get('password');
                            @endphp
                        @if ($messages_password)
                            <ul style="color:red;">
                                @foreach ((array) $messages_password as $message_password)
                                    <li>{{ $message_password }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="update_password_password_confirmation"
                            class="form-label">{{ __('Confirm Password') }}</label>
                        <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                            autocomplete="new-password" class="form-control">
                            @php
                            $passwords_confirmation = $errors->updatePassword->get('password_confirmation');
                            @endphp
                        @if ($passwords_confirmation)
                            <ul>
                                @foreach ((array) $passwords_confirmation as $password_confirmation)
                                    <li>{{ $password_confirmation }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    {{-- <div class="input-group mb-3">
                        <input type="file" class="form-control" id="inputGroupFile02">
                        <label class="input-group-text" for="inputGroupFile02">Upload</label>
                    </div> --}}

                </div>
                <!--end::Body-->
                <!--begin::Footer-->
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <!--end::Footer-->
            </form>
            <!--end::Form-->
        </div>

    </div>

    {{-- <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password"
                class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form> --}}
</section>
