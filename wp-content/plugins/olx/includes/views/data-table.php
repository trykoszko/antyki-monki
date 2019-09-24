<?php
?>
<div class="wrap">
    <h1>OLX</h1>
    <h2 class="nav-tab-wrapper">
        <a href="?page=olx-admin" class="nav-tab nav-tab-green<?php echo !isset($_GET['tab']) ? ' nav-tab-active' : ''; ?>">Aktywne ogłoszenia</a>
        <a href="?page=olx-admin&tab=import" class="nav-tab<?php echo isset($_GET['tab']) && $_GET['tab'] == 'import' ? ' nav-tab-active' : ''; ?>">Importuj z OLX</a>
    </h2>
    <br>
    <?php if ( !isset($_GET['tab'])) : ?>
        <div>
            <table class="olx-data-table" border="0">
                <thead>
                    <tr>
                        <td class="olx-table-order">#</td>
                        <td class="olx-table-id">ID</td>
                        <td class="olx-table-title">Nazwa</td>
                        <td class="olx-table-price">Cena</td>
                        <td class="olx-table-validfrom">Ważne od</td>
                        <td class="olx-table-validto">Ważne do</td>
                        <td class="olx-table-photo">Zdjęcie</td>
                        <td class="olx-table-status">Status</td>
                        <td class="olx-table-actions">Akcje</td>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                        <?php foreach ($adverts as $advert) : ?>
                            <?php
                                $ad_status = 'Nieaktywne';
                                switch ($advert->status) :
                                    case 'active' :
                                        $ad_status = '<span style="color: green;">Aktywne</span>';
                                        break;
                                    case 'removed_by_user' :
                                        $ad_status = '<span style="color: red;">Wyłączone</span>';
                                        break;
                                    case 'outdated' :
                                        $ad_status = '<span style="color: blue;">Przeterminowane</span>';
                                        break;
                                endswitch;
                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><a target="_blank" href="<?php echo $advert->url; ?>"><?php echo $advert->id; ?></a></td>
                                <td><?php echo $advert->title; ?></td>
                                <td><?php echo $advert->price->value; ?> zł</td>
                                <td><?php echo $advert->activated_at; ?></td>
                                <td><?php echo $advert->valid_to; ?></td>
                                <td><a target="_blank" href="<?php echo $advert->url; ?>"><img src="<?php echo $advert->images[0]->url; ?>" style="width: 200px; height: auto;" /></a></td>
                                <td><?php echo $ad_status; ?></td>
                                <td><a target="_blank" href="https://www.olx.pl/nowe-ogloszenie/edit/<?php echo $advert->id; ?>">Edytuj ogłoszenie</a></td>
                            </tr>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php elseif ( $_GET['tab'] == 'import' ) : ?>
        <?php if ( !isset($_GET['import_action']) || !$_GET['import_action'] ) : ?>
            <p>
                Import ogłoszeń z OLX
            </p>
            <script>
                let isConfirmed = confirm('Jesteś pewny, ze chcesz zaimportowac produkty z OLX? Potrwa to trochę, poza tym moze nadpisac dotychczasowe produkty.');
                if (isConfirmed) {
                    window.location.href += '?import_action=true'
                } else {
                    window.history.go(-1)
                }
            </script>
        <?php elseif ( $_GET['import_action'] ) : ?>
            <p>
                <?php
                    $this->sync_ads_to_posts();
                ?>
            </p>
        <?php endif; ?>
    <?php else : ?>
        return;
    <?php endif; ?>
</div>
