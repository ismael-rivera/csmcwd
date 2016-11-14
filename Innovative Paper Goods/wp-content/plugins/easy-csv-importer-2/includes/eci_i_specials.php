<br />
<form method="post" name="eci_specialfunctions_form" action="">            
     <table>
      <tr>
            <td><b>Functions</b></td>
            <td><b>CSV Columns</b></td>
        </tr>				
        <tr>
            <td>Featured Images</td>
            <td><?php eci_csvcolumnmenu_specialfunctions( $pro['current'], 'thumbnail',$pro );?> (paid edition only,trying to use this feature may cause problems)</td>
        </tr>
        
        <?php if( ECI_EFF_HOOKSFILE ){?>				
        <tr>
            <td>URL Cloaking</td>
            <td><?php eci_csvcolumnmenu_specialfunctions( $pro['current'], 'cloaking1',$pro );?> (paid edition only,trying to use this feature may cause problems)</td>
        </tr>
        <?php }?>
        <tr>
            <td>Permalink/Slug/Post Name</td>
            <td><?php eci_csvcolumnmenu_specialfunctions( $pro['current'],'permalink',$pro );?></td>
        </tr>
        <tr>
            <td>Pre-Made Dates</td>
            <td><?php eci_csvcolumnmenu_specialfunctions( $pro['current'],'dates',$pro );?></td>
        </tr>     
        <tr>
            <td>Pre-Made Tags</td>
            <td><?php eci_csvcolumnmenu_specialfunctions( $pro['current'],'madetags',$pro );?></td>
        </tr>	              
        <tr>
            <td>Tag Generator</td>
            <td><?php eci_csvcolumnmenu_specialfunctions( $pro['current'],'tags',$pro );?></td>
        </tr>                       
        <tr>
            <td>Excerpt Generator</td>
            <td><?php eci_csvcolumnmenu_specialfunctions( $pro['current'],'excerpt',$pro );?></td>
        </tr>   	
        <h4>Paid edition now allows any splitter character, coming soon too free edition</h4>			
        <tr>
            <td>Category Splitter ( / )</td>
            <td><?php eci_csvcolumnmenu_specialfunctions( $pro['current'],'catsplitter',$pro );?></td>
        </tr>  

    </table>
    <input class="button-primary" type="submit" name="eci_specialfunctions_submit" value="Save" />
</form>
<br />
