<?php

class SeoMeta extends Model
{
    protected string $table = 'seo_meta';

    public function getByPage(string $identifier): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM seo_meta WHERE page_identifier = ? LIMIT 1");
        $stmt->execute([$identifier]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findAllOrdered(): array
    {
        return $this->findAll([], 'page_identifier ASC');
    }
}
