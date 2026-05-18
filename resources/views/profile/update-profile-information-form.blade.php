<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" class="hidden"
                            wire:model="photo"
                            x-ref="photo"
                            x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-label for="photo" value="{{ __('frontend.photo') }}" />

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-secondary-button class="mt-2 mr-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('frontend.select_new_photo') }}
                </x-secondary-button>

                @if (auth()->user()->profile_photo_path)
                    <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('frontend.remove_photo') }}
                    </x-secondary-button>
                @endif

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('frontend.name') }}" />
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="state.name" required autocomplete="name" />
            <x-input-error for="state.name" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('frontend.email') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model.defer="state.email" required autocomplete="username" />
            <x-input-error for="state.email" class="mt-2" />

            @if (! auth()->user()->is_verified)
                <p class="text-sm mt-2 dark:text-white">
                    {{ __('frontend.your_email_is_unverified') }}

                    <a href="{{ route('verification.notice') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                        {{ __('frontend.click_here_to_verify_email') }}
                    </a>
                </p>
            @endif
        </div>

        <!-- Phone -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="mobile" value="{{ __('frontend.phone') }}" />
            <x-input id="mobile" type="text" class="mt-1 block w-full" wire:model.defer="state.mobile" autocomplete="mobile" />
            <x-input-error for="state.mobile" class="mt-2" />
        </div>

        <!-- Country -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="country" value="{{ __('frontend.country') }}" />
            <select wire:model.defer="state.country" 
                    wire:change="updatedStateCountry($event.target.value)"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                    id="country">
                <option value="">{{ __('frontend.select_country') }}</option>
                @foreach($countries as $id => $name)
                    <option value="{{ $id }}" {{ $state['country'] == $id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
            <x-input-error for="state.country" class="mt-2" />
        </div>

        <!-- State -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="state" value="{{ __('frontend.state_province') }}" />
            <select wire:model.defer="state.state" 
                    wire:change="updatedStateState($event.target.value)"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                    id="state"
                    {{ empty($states) ? 'disabled' : '' }}>
                <option value="">{{ __('frontend.select_state_province') }}</option>
                @foreach($states as $id => $name)
                    <option value="{{ $id }}" {{ $state['state'] == $id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
            <x-input-error for="state.state" class="mt-2" />
        </div>

        <!-- City -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="city" value="{{ __('frontend.city') }}" />
            <select wire:model.defer="state.city" 
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                    id="city"
                    {{ empty($cities) ? 'disabled' : '' }}>
                <option value="">{{ __('frontend.select_city') }}</option>
                @foreach($cities as $id => $name)
                    <option value="{{ $id }}" {{ $state['city'] == $id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
            <x-input-error for="state.city" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="mr-3" on="saved">
            {{ __('frontend.saved') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('frontend.save') }}
        </x-button>
    </x-slot>
</x-form-section>
