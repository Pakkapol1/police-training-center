<?php
require_once __DIR__ . '/BaseModel.php';

class OfficerDirectoryModel extends BaseModel {
    private $table = 'officer_directory';
    private $enlistedTable = 'officer_directory_enlisted';

    public function getLatest() {
        $sql = "SELECT * FROM {$this->table} ORDER BY uploaded_at DESC LIMIT 1";
        return $this->fetch($sql);
    }

    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY uploaded_at DESC";
        return $this->fetchAll($sql);
    }

    public function getLatestEnlisted() {
        $sql = "SELECT * FROM {$this->enlistedTable} ORDER BY uploaded_at DESC LIMIT 1";
        return $this->fetch($sql);
    }

    public function getAllEnlisted() {
        $sql = "SELECT * FROM {$this->enlistedTable} ORDER BY uploaded_at DESC";
        return $this->fetchAll($sql);
    }

    public function add($title, $word_file, $pdf_file, $uploaded_by, $original_word_name = null, $original_pdf_name = null) {
        $uploaded_at = TimeHelper::now();
        $sql = "INSERT INTO {$this->table} (title, word_file, pdf_file, uploaded_by, uploaded_at, original_word_name, original_pdf_name) VALUES (?, ?, ?, ?, ?, ?, ?)";
        try {
            return $this->execute($sql, [$title, $word_file, $pdf_file, $uploaded_by, $uploaded_at, $original_word_name, $original_pdf_name]);
        } catch (\PDOException $e) {
            error_log('OfficerDirectoryModel::add SQL ERROR: ' . $e->getMessage());
            error_log('PARAMS: ' . print_r([$title, $word_file, $pdf_file, $uploaded_by, $uploaded_at, $original_word_name, $original_pdf_name], true));
            throw $e;
        }
    }

    public function update($id, $title, $word_file, $pdf_file) {
        $sql = "UPDATE {$this->table} SET title=?, word_file=?, pdf_file=?, uploaded_at=NOW() WHERE id=?";
        return $this->execute($sql, [$title, $word_file, $pdf_file, $id]);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id=?";
        return $this->execute($sql, [$id]);
    }

    public function addEnlisted($title, $word_file, $pdf_file, $uploaded_by, $original_word_name = null, $original_pdf_name = null) {
        $uploaded_at = TimeHelper::now();
        $sql = "INSERT INTO {$this->enlistedTable} (title, word_file, pdf_file, uploaded_by, uploaded_at, original_word_name, original_pdf_name) VALUES (?, ?, ?, ?, ?, ?, ?)";
        try {
            return $this->execute($sql, [$title, $word_file, $pdf_file, $uploaded_by, $uploaded_at, $original_word_name, $original_pdf_name]);
        } catch (\PDOException $e) {
            error_log('OfficerDirectoryModel::addEnlisted SQL ERROR: ' . $e->getMessage());
            error_log('PARAMS: ' . print_r([$title, $word_file, $pdf_file, $uploaded_by, $uploaded_at, $original_word_name, $original_pdf_name], true));
            throw $e;
        }
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id=?";
        return $this->fetch($sql, [$id]);
    }

    public function getEnlistedById($id) {
        $sql = "SELECT * FROM {$this->enlistedTable} WHERE id=?";
        return $this->fetch($sql, [$id]);
    }
    public function deleteEnlisted($id) {
        $sql = "DELETE FROM {$this->enlistedTable} WHERE id=?";
        return $this->execute($sql, [$id]);
    }
} 