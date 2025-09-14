package com.example.demo.data.repositories;

import com.example.demo.data.schema.ProductSchema;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.math.BigDecimal;
import java.util.List;

@Repository
public interface ProductRepository extends JpaRepository<ProductSchema, Long> {
    List<ProductSchema> findByNameContaining(String nameFragment);
    List<ProductSchema> findByCategoryName(String categoryFragment);
    List<ProductSchema> findByPriceBetween(BigDecimal price, BigDecimal price2);
}
