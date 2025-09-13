package com.example.demo.data.repositories;

import com.example.demo.data.schema.ProductSchema;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface ProductRepository extends JpaRepository<ProductSchema, Long> {
    List<ProductSchema> findByNameContaining(String nameFragment);
    List<ProductSchema> findByCategoryContaining(String categoryFragment);
    List<ProductSchema> findByPriceBetween(Double minPrice, Double maxPrice);
    List<ProductSchema> findByTags(String tagFragment);
}
