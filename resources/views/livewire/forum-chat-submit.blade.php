<div x-data="{ isUploading: false, progress: 0 }"
     x-on:livewire-upload-start="isUploading = true"
     x-on:livewire-upload-finish="isUploading = false"
     x-on:livewire-upload-error="isUploading = false"
     x-on:livewire-upload-progress="progress = $event.detail.progress">

    <form wire:submit.prevent="save">

        <label class="btn tia_icon">
            <input type="file" id="files" name="files" wire:model="pic" multiple accept=".png, .jpg, .jpeg" style="cursor: pointer; display: none;">
        </label>
        <div class="ta_input">
            <div class="ta_input_a">
                {{--<a href="javascript:void(0);" class="ta_yyah" disabled="disabled">--}}
                {{--<img src="/posts/images/yyqh.png">--}}

                <input type="color" name="color" id="color" wire:model.defer="color" style="position: absolute;width: 25px; top: 5px; left: 8px;">
{{--                <div id="cp">--}}
{{--                    <input type="hidden" class="form-control input-lg" name="color" id="color" wire:model="color" value="#000000"/>--}}
{{--                    <span class="input-group-append">--}}
{{--                    <span class="input-group-text colorpicker-input-addon"><i></i></span>--}}
{{--                    </span>--}}
{{--                </div>--}}
                <input placeholder="請輸入內容" class="ta_input_po" id="content" wire:model="content">
            </div>
        </div>
        <button class="fs_icon" id="submit" type="submit"></button>
        <input type="hidden" wire:model="forum_id" id="forum_id" value={{$forum_id}}>
        <div x-show="isUploading" style="padding-top: 40px; display: flex;">
            <progress max="100" x-bind:value="progress"></progress>
        </div>
        <div style="padding-top: 40px; display: block;">
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


@push('scripts')
    <script>
        let temp_color;
        $('#color').on('change', function (){
            temp_color = $(this).val();
           // alert($(this).val());
           $('.ta_input_po').css('color', $(this).val());
        });

        $("#content").on('change', function (){
            // alert(temp_color);
            $('.ta_input_po').css('color', temp_color);
        });
    </script>
@endpush