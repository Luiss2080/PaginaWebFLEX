<?php

class Model
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $hidden = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        return $this->db->selectOne($sql, ['id' => $id]);
    }

    public function findBy($column, $value)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = :value LIMIT 1";
        return $this->db->selectOne($sql, ['value' => $value]);
    }

    public function all($columns = '*')
    {
        $sql = "SELECT {$columns} FROM {$this->table}";
        return $this->db->select($sql);
    }

    public function where($conditions, $params = [])
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$conditions}";
        return $this->db->select($sql, $params);
    }

    public function paginate($page = 1, $perPage = 10, $conditions = '', $params = [])
    {
        $offset = ($page - 1) * $perPage;
        
        $countSql = "SELECT COUNT(*) as total FROM {$this->table}";
        if ($conditions) {
            $countSql .= " WHERE {$conditions}";
        }
        
        $total = $this->db->selectOne($countSql, $params)['total'];
        
        $sql = "SELECT * FROM {$this->table}";
        if ($conditions) {
            $sql .= " WHERE {$conditions}";
        }
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";
        
        $data = $this->db->select($sql, $params);
        
        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage),
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total)
        ];
    }

    public function create($data)
    {
        $filteredData = $this->filterFillable($data);
        return $this->db->insert($this->table, $filteredData);
    }

    public function update($id, $data)
    {
        $filteredData = $this->filterFillable($data);
        return $this->db->update(
            $this->table, 
            $filteredData, 
            "{$this->primaryKey} = :id", 
            ['id' => $id]
        );
    }

    public function delete($id)
    {
        return $this->db->delete(
            $this->table, 
            "{$this->primaryKey} = :id", 
            ['id' => $id]
        );
    }

    public function exists($id)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $result = $this->db->selectOne($sql, ['id' => $id]);
        return $result['count'] > 0;
    }

    protected function filterFillable($data)
    {
        if (empty($this->fillable)) {
            return $data;
        }

        return array_intersect_key($data, array_flip($this->fillable));
    }

    protected function hideFields($data)
    {
        if (empty($this->hidden)) {
            return $data;
        }

        foreach ($this->hidden as $field) {
            unset($data[$field]);
        }

        return $data;
    }

    public function search($query, $columns = [])
    {
        if (empty($columns)) {
            return [];
        }

        $conditions = [];
        $params = [];

        foreach ($columns as $column) {
            $conditions[] = "{$column} LIKE :query";
        }

        $sql = "SELECT * FROM {$this->table} WHERE " . implode(' OR ', $conditions);
        $params['query'] = "%{$query}%";

        return $this->db->select($sql, $params);
    }

    public function orderBy($column, $direction = 'ASC')
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$column} {$direction}";
        return $this->db->select($sql);
    }
}