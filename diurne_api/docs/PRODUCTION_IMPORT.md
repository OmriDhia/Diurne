# Production Database Import Procedure

## Method 1: Direct Transfer (Recommended)

### Step 1: Get Production Database Dump
```bash
# On production server:
mysqldump --single-transaction --quick \
  -h [PROD_DB_HOST] -u [PROD_DB_USER] -p \
  [PROD_DB_NAME] | gzip > prod_dump_$(date +%Y%m%d).sql.gz

# Or using .env variables:
mysqldump --single-transaction --quick \
  -h $PROD_DB_HOST -u $PROD_DB_USER -p$PROD_DB_PASSWORD \
  $PROD_DB_NAME > prod_dump_$(date +%Y%m%d).sql
```

### Step 2: Secure Transfer
```bash
# Using SCP (replace with your secure method):
scp user@prod-server:/path/to/prod_dump.sql.gz ./docker/mysql/dumps/
```

### Step 3: Local Import
```bash
# For compressed dumps:
gunzip < docker/mysql/dumps/prod_dump.sql.gz | \
  docker-compose exec -T db mysql -u root [LOCAL_DB_NAME]

# For regular SQL:
docker-compose exec db mysql -u root [LOCAL_DB_NAME] < docker/mysql/dumps/prod_dump.sql
```

## Method 2: Using Database Tools
1. Export from production using MySQL Workbench/TablePlus
2. Save to `docker/mysql/dumps/prod_export.sql`
3. Import using:
   ```bash
   docker-compose exec db mysql -u root [LOCAL_DB_NAME] < docker/mysql/dumps/prod_export.sql
   ```

## Post-Import Steps
1. Sanitize sensitive data:
   ```bash
   docker-compose exec api bin/console app:sanitize-data
   ```
2. Clear caches:
   ```bash
   docker-compose exec api bin/console cache:clear
   ```

⚠️ **Important Security Notes**:
- Never commit production database dumps to Git
- Delete local dumps after import
- Use `docker/mysql/dumps/.gitkeep` to maintain directory