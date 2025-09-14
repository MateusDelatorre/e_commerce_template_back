package com.example.demo.domain.entities.product;

import java.math.BigDecimal;
import java.util.List;

public interface IProductRepository {
    Product save(Product product);
    Product findById(Long id);
    List<Product> findByCategory(String category);
    List<Product> findByName(String name);
    List<Product> findByPriceRange(BigDecimal minPrice, BigDecimal maxPrice);
    void deleteById(Long id);
}
