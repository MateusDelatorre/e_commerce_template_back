package com.example.demo.domain.entities.product;

import java.util.List;

public interface IProductRepository {
    Product save(Product product);
    Product findById(Long id);
    List<Product> findByCategory(String category);
    List<Product> findByName(String name);
    List<Product> findByPriceRange(Double minPrice, Double maxPrice);
    List<Product> findByTag(String tag);
    List<Product> findAll();
    void deleteById(Long id);
}
