patterns
========

study patterns's programming


INSERTING
#0 woo\mapper\VenueMapper->doInsert(
    woo\domain\Venue Object (
        [] => The Likely Lounge,
        [] => woo\mapper\SpaceCollection Object (
            [] => ,
            [] => 0,
            [] => Array (),
            [] => ,
            [] => 0,
            [] => Array ()),
        [] => -1))
    called at [/Applications/MAMP/htdocs/patterns/GITpatterns/woo/mapper/Mapper.php:94]

#1 woo\mapper\Mapper->insert(
    woo\domain\Venue Object (
        [] => The Likely Lounge,
        [] => woo\mapper\SpaceCollection Object (
            [] => ,
            [] => 0,
            [] => Array (),
            [] => ,
            [] => 0,
            [] => Array ()),
        [] => -1))
    called at [/Applications/MAMP/htdocs/patterns/GITpatterns/test.php:98]

    woo\domain\Venue Object (
        [name:woo\domain\Venue:private] => The Likely Lounge [spaces:woo\domain\Venue:private] => woo\mapper\SpaceCollection Object ( [mapper:protected] => woo\mapper\SpaceMapper Object ( [selectAllStmt] => PDOStatement Object ( [queryString] => SELECT * FROM space ) [selectStmt] => PDOStatement Object ( [queryString] => SELECT * FROM space WHERE id=? ) [updateStmt] => PDOStatement Object ( [queryString] => UPDATE space SET name=?, id=? WHERE id=? ) [insertStmt] => PDOStatement Object ( [queryString] => INSERT INTO space (name, venue) VALUES( ?, ?) ) [findByVenueStmt] => PDOStatement Object ( [queryString] => SELECT * FROM space WHERE venue=? ) ) [total:protected] => 0 [raw:protected] => Array ( ) [result:woo\mapper\Collection:private] => [pointer:woo\mapper\Collection:private] => 0 [objects:woo\mapper\Collection:private] => Array ( ) ) [id:woo\domain\DomainObject:private] => 8 ) UPDATING #0 woo\mapper\VenueMapper->update(woo\domain\Venue Object ([] => The Bibble Beer Likely Loung,[] => woo\mapper\SpaceCollection Object ([] => woo\mapper\SpaceMapper Object ([selectAllStmt] => PDOStatement Object ([queryString] => SELECT * FROM space),[selectStmt] => PDOStatement Object ([queryString] => SELECT * FROM space WHERE id=?),[updateStmt] => PDOStatement Object ([queryString] => UPDATE space SET name=?, id=? WHERE id=?),[insertStmt] => PDOStatement Object ([queryString] => INSERT INTO space (name, venue) VALUES( ?, ?)),[findByVenueStmt] => PDOStatement Object ([queryString] => SELECT * FROM space WHERE venue=?)),[] => 0,[] => Array (),[] => ,[] => 0,[] => Array ()),[] => 8)) called at [/Applications/MAMP/htdocs/patterns/GITpatterns/test.php:103] woo\domain\Venue Object ( [name:woo\domain\Venue:private] => The Bibble Beer Likely Loung [spaces:woo\domain\Venue:private] => woo\mapper\SpaceCollection Object ( [mapper:protected] => woo\mapper\SpaceMapper Object ( [selectAllStmt] => PDOStatement Object ( [queryString] => SELECT * FROM space ) [selectStmt] => PDOStatement Object ( [queryString] => SELECT * FROM space WHERE id=? ) [updateStmt] => PDOStatement Object ( [queryString] => UPDATE space SET name=?, id=? WHERE id=? ) [insertStmt] => PDOStatement Object ( [queryString] => INSERT INTO space (name, venue) VALUES( ?, ?) ) [findByVenueStmt] => PDOStatement Object ( [queryString] => SELECT * FROM space WHERE venue=? ) ) [total:protected] => 0 [raw:protected] => Array ( ) [result:woo\mapper\Collection:private] => [pointer:woo\mapper\Collection:private] => 0 [objects:woo\mapper\Collection:private] => Array ( ) ) [id:woo\domain\DomainObject:private] => 8 )