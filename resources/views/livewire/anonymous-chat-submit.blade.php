<div x-data="{ isUploading: false, progress: 0 }"
     x-on:livewire-upload-start="isUploading = true"
     x-on:livewire-upload-finish="isUploading = false"
     x-on:livewire-upload-error="isUploading = false"
     x-on:livewire-upload-progress="progress = $event.detail.progress">
    {{-- Care about people's approval and you will be their prisoner. --}}
    <form wire:submit.prevent="save">
            <div class="xin_left">
{{--                <a class="xin_nleft "><img src="/new/images/moren_pic.png"></a>--}}
                <label class="btn xin_nleft">
                    <img src="/new/images/moren_pic.png">
                    <input type="file" id="files" name="files" wire:model="pic" multiple accept=".png, .jpg, .jpeg" style="cursor: pointer; display: none;">
                </label>
                <input class="xin_input" placeholder="請輸入" id="content" wire:model="content">
            </div>
{{--        <div class="xin_right"><img src="/new/images/fasong.png"></div>--}}
        <button class="xin_right" id="submit" type="submit" style="border: unset;"><img src="/new/images/fasong.png"></button>
        <div x-show="isUploading" style="padding-top: 40px; display: flex;">
            <progress max="100" x-bind:value="progress"></progress>
        </div>
        <div style="padding-top: 45px; display: block;">
            @if ($pic && count($pic)<=5)
                @foreach($pic as $row)
                    <div wire:key="{{$loop->index}}" class="tempImg">
                        <img src="{{ $row->temporaryUrl() }}">
                        <button wire:click.prevent="removeMe({{$loop->index}})" class="removeImg" title="移除"><i style="font-size:24px" class="fa">&#xf00d;</i></button>
                    </div>
                @endforeach
            @elseif($pic && count($pic)>5)
                <script>
                    c5('至多上傳5張圖片');
                </script>
                <div wire:init="removeAll({{count($pic)}})"></div>
            @endif
        </div>
        @error('pic.*') <span class="error">{{ $message }}</span> @enderror
    </form>
    <spna style="font-size: 10px; color: red;">一次最多上傳五張照片，重新選取照片將刪除之前選的</spna>
</div>
