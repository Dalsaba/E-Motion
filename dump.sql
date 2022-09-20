SET FOREIGN_KEY_CHECKS = 0;

 The following SQL statements will be executed:

     DROP TABLE doctrine_migration_versions;
     DROP TABLE location_vehicule;
     ALTER TABLE vehicule DROP classe_id, CHANGE id id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)';
