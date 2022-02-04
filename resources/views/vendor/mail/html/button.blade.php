<table class="action" align="center" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center">
                        <table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>
                                    <?php
                                        $c='#008CBA';
                                        switch ($color)
                                        {
                                            case 'red':
                                                $c = '#f44336';
                                                break;
                                            case 'green':
                                                $c = '#4CAF50';
                                                break;
                                            case 'blue':
                                                $c = '#008CBA';
                                        }
                                    ?>
                                    <a href="{{ $url }}" 
                                       class="button" 
                                       style="background-color:{{$c}}; width:120px; height:40px; text-align: center; line-height:40px;"
                                       >
                                       {{ $slot }}
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>   
</table>
